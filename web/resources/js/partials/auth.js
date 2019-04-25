export function login(credentials) {
  return new Promise((res, rej) => {
    axios
      .post('/api/auth/login', credentials)
      .then(response => {
        res(response.data)
      })
      .catch(err => {
        rej(err.response.data)
      })
  })
}

export function getLoggedinUser() {
  return new Promise((res, rej) => {
    axios
      .get('/api/auth/me')
      .then(response => {
        res(response.data)
      })
      .catch(err => {
        rej('Unautorized.');
      })
  })
}
