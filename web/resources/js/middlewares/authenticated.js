export default function authenticated({ next, router }) {
  if (!localStorage.getItem('access_token')) {
    return router.push({ name: 'login' });
  }

  return next();
}
