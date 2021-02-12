import { userLogin } from './helpers/apiHelper.js';

/**
 * Test Error Logins
 */
test('User fetch login with bad email or password', () => {
  return userLogin('no@no.com','asdsad').then(data => {
    expect(data.success).toBe(false);
  });
})