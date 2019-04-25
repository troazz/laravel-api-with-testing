import authenticated from './middlewares/authenticated'
import guest from './middlewares/guest'
import Login from './components/Login.vue'
import { Amenity, AmenityAdd, AmenityUpdate } from './components/amenity'
import Dashboard from './components/Dashboard.vue'
import PageNotFound from './components/PageNotFound.vue'
import { Currency, CurrencyAdd, CurrencyUpdate } from './components/currency'
import { Hotel, HotelAdd, HotelUpdate } from './components/hotel'

export const routes = [
  {
    path: '/',
    name: 'login',
    meta: {
      middleware: guest
    },
    component: Login
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    meta: {
      middleware: authenticated
    },
    component: Dashboard
  },
  {
    path: '/amenities',
    name: 'amenities',
    meta: {
      middleware: authenticated
    },
    component: Amenity
  },
  {
    path: '/amenities/add',
    name: 'amenities.add',
    meta: {
      middleware: authenticated
    },
    component: AmenityAdd
  },
  {
    path: '/amenities/update/:id',
    name: 'amenities.update',
    meta: {
      middleware: authenticated
    },
    component: AmenityUpdate
  },
  {
    path: '/currencies',
    name: 'currencies',
    meta: {
      middleware: authenticated
    },
    component: Currency
  },
  {
    path: '/currencies/add',
    name: 'currencies.add',
    meta: {
      middleware: authenticated
    },
    component: CurrencyAdd
  },
  {
    path: '/currencies/update/:id',
    name: 'currencies.update',
    meta: {
      middleware: authenticated
    },
    component: CurrencyUpdate
  },
  {
    path: '/hotels',
    name: 'hotels',
    meta: {
      middleware: authenticated
    },
    component: Hotel
  },
  {
    path: '/hotels/add',
    name: 'hotels.add',
    meta: {
      middleware: authenticated
    },
    component: HotelAdd
  },
  {
    path: '/hotels/update/:id',
    name: 'hotels.update',
    meta: {
      middleware: authenticated
    },
    component: HotelUpdate
  },
  { path: '*', component: PageNotFound }
]
