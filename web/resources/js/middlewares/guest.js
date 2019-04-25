export default function guest({ next, router }) {
  if (localStorage.getItem('access_token')) {
    return router.push({ name: 'dashboard' });
  }

  return next();
}
