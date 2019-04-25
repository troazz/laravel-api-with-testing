<?php

namespace App\Repositories;

use App\Models\Hotel;
use Exception;
use Throwable;
use Validator;
use Cache;
use App\Models\Room;

class HotelRepository extends Repository
{
    public function __construct()
    {
        $this->class      = Hotel::class;
        $this->createRule = [
            'name'                  => 'required|string',
            'description'           => 'nullable|string',
            'address'               => 'required|string',
            'latitude'              => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude'             => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'commission_type'       => 'required|in:percentage,exact',
            'commission_amount'     => 'required|numeric',
            'rooms'                 => 'nullable|array|max:5',
            'rooms.*.name'          => 'required|string',
            'rooms.*.description'   => 'nullable|string',
            'rooms.*.access_code'   => 'required|size:7',
            'rooms.*.max_occupancy' => 'required|digits_between:1,255',
            'rooms.*.net_rate'      => 'required|numeric',
            'rooms.*.currency_id'   => 'required|exists:currencies,id',
            'rooms.*.amenities_id'  => 'nullable|array|exists:amenities,id',
        ];
        $this->updateRule = [
            'name'                  => 'filled|string',
            'description'           => 'nullable|string',
            'address'               => 'filled|string',
            'latitude'              => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'longitude'             => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'commission_type'       => 'filled|in:percentage,exact',
            'commission_amount'     => 'filled|numeric',
            'rooms'                 => 'nullable|array|max:5',
            'rooms.*.id'            => 'nullable|numeric|exists:rooms,id',
            'rooms.*.name'          => 'nullable|string',
            'rooms.*.description'   => 'nullable|string',
            'rooms.*.access_code'   => 'filled|size:7',
            'rooms.*.max_occupancy' => 'filled|digits_between:1,255',
            'rooms.*.net_rate'      => 'filled|numeric',
            'rooms.*.currency_id'   => 'filled|exists:currencies,id',
            'rooms.*.amenities_id'  => 'nullable|array|exists:amenities,id',
        ];
        $this->searchable = ['name', 'description'];
        $this->with = ['rooms.currency', 'rooms.amenities'];
        parent::__construct();
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data, $this->createRule);

            if ($validator->fails()) {
                return $this->invalidated($validator->errors());
            }

            $hotel = $this->_save(null, $data);
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }
        $hotel->load(['rooms.currency', 'rooms.amenities']);

        return $this->created($hotel);
    }

    public function update($id, $data)
    {
        try {
            $validator = Validator::make($data, $this->createRule);

            if ($validator->fails()) {
                return $this->invalidated($validator->errors());
            }
            $hotel = $this->_save($id, $data);
            Cache::forget($this->key . '_' . $id);
        } catch (NotFoundHttpException $e) {
            return $this->notFound();
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }
        $hotel->load(['rooms.currency', 'rooms.amenities']);

        return $this->ok($hotel);
    }

    private function _save($id, $data)
    {
        $hotel = $id ? $this->_get($id) : new $this->class();
        $hotel->fill($data);
        $hotel->save();

        $rooms = collect($data['rooms']);
        if ($rooms->count()) {
            $rooms = $rooms->map(function($arr) use ($hotel) {
                if (empty($arr['id'])) {
                    $room = $hotel->rooms()->create($arr);
                } else {
                    $room = Room::where('id', $arr['id'])->where('hotel_id', $hotel->id)->first();
                    $room->fill($arr);
                    $room->save();
                }
                if (!empty($arr['amenities_id'])) {
                    $room->amenities()->sync($arr['amenities_id']);
                }

                return $room;
            });

            if ($id) {
                Room::where('hotel_id', $hotel->id)
                    ->whereNotIn('id', $rooms->pluck('id'))
                    ->delete();
            }
        }

        return $hotel;
    }
}
