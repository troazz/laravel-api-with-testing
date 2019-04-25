<?php

namespace App\Repositories;

use Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use Throwable;
use Validator;

class Repository
{
    use ResponseCodes;
    protected $limit = 10;

    protected $class;
    protected $createRule;
    protected $updateRule;
    protected $key;
    protected $with;
    protected $searchable;
    protected $default;

    public function __construct()
    {
        if (! empty($this->class)) {
            $tmp       = collect(explode('\\', $this->class));
            $this->key = strtolower($tmp->last());
        }
    }

    public function get($params)
    {
        $validator = Validator::make($params, [
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
            'q' => 'string|nullable',
            'sort' => ['nullable', function($attribute, $value, $fail) {
                if ($value) {
                    $tmp = explode('-', $value);
                    if (empty($tmp[1]) || !in_array($tmp[1], ['asc', 'desc'])) {
                        $fail($attribute. ' must contain column and direction. Like this name-asc.');
                    }
                }
            }]
        ]);

        if ($validator->fails()) {
            return $this->invalidated($validator->errors());
        }

        extract($params);
        if (empty($page)) {
            $page = 1;
        }
        if (empty($per_page)) {
            $per_page = $this->limit;
        }
        if (empty($q)) {
            $q = '';
        }
        if (empty($sort)) {
            $sort = 'id-asc';
        }

        try {
            $obj = Cache::remember($this->key . '_' . $page . '_' . $per_page . '_' . $q.'_'.$sort, config('cache.duration'), function () use ($params, $page, $per_page, $q, $sort) {
                list($column, $direction) = explode('-', $sort);
                $model = $this->class::orderBy($column, $direction);
                if (! empty($this->with)) {
                    $model = $model->with($this->with);
                }
                if (! empty($this->searchable)) {
                    $model = $model->where(function ($query) use ($q) {
                        foreach ($this->searchable as $column) {
                            $query->OrWhere($column, 'LIKE', "%$q%");
                        }
                    });
                }

                if ($per_page) {
                    return $model->paginate($per_page, ['*'], 'page', $page)->appends($params);
                } else {
                    return $model->get();
                }
            });
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }

        return $this->ok($obj);
    }

    public function create($data)
    {
        try {
            $validator = Validator::make($data, $this->createRule);

            if ($validator->fails()) {
                return $this->invalidated($validator->errors());
            }

            $obj = new $this->class();
            $obj->fill($data);
            $obj->save();
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }

        return $this->created($obj->toArray());
    }

    public function update($id, $data)
    {
        try {
            $validator = Validator::make($data, $this->updateRule);

            if ($validator->fails()) {
                return $this->invalidated($validator->errors());
            }

            $obj = $this->_get($id);
            $obj->fill($data);
            $obj->save();
            Cache::forget($this->key . '_' . $id);
        } catch (NotFoundHttpException $e) {
            return $this->notFound();
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }

        return $this->ok($obj->toArray());
    }

    public function delete($id)
    {
        try {
            $obj = $this->_get($id);
            $obj->delete();
            Cache::forget($this->key . '_' . $id);
        } catch (NotFoundHttpException $e) {
            return $this->notFound();
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }

        return $this->ok(['message' => 'Delete success.']);
    }

    public function find($id)
    {
        try {
            $obj = Cache::remember($this->key . '_' . $id, config('cache.duration'), function () use ($id) {
                return $this->_get($id, true);
            });
        } catch (NotFoundHttpException $e) {
            return $this->notFound();
        } catch (Exception | Throwable $e) {
            return $this->ise(['message' => $e->getMessage()]);
        }

        return $this->ok($obj);
    }

    protected function _get($id, $withRelations = false)
    {
        $obj = $this->class::find($id);
        if (! empty($this->with) && $withRelations && $obj) {
            $obj = $obj->load($this->with);
        }
        if (! $obj) {
            throw new NotFoundHttpException('');
        }

        return $obj;
    }
}
