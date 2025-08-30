import { test, expect } from '@playwright/test';

test.describe('Multisite Plugin', () => {
  test('should verify multisite functionality and REST API', async ({ page }) => {
    // Test REST API endpoint first (no auth required)
    const response = await page.request.get('http://localhost:8080/wp-json/acme/v1/example');
    expect(response.status()).toBe(200);

    const data = await response.json();
    expect(data.message).toBe('Hello from ACME plugin!');
    expect(data.is_multisite).toBe(true);
    expect(data.blog_id).toBe(1);

    // Test WordPress frontend is accessible
    await page.goto('http://localhost:8080/');
    await expect(page.locator('body')).toBeVisible();

    // Test admin login page is accessible
    await page.goto('http://localhost:8080/wp-admin/');
    await expect(page.locator('#user_login')).toBeVisible();
    await expect(page.locator('#user_pass')).toBeVisible();
    await expect(page.locator('#wp-submit')).toBeVisible();

    // Test that we can login (basic functionality)
    await page.fill('#user_login', 'admin');
    await page.fill('#user_pass', 'admin');
    await page.click('#wp-submit');

    // Wait for dashboard to load
    await expect(page).toHaveURL(/.*wp-admin/);
    
    // Verify we're logged in by checking for admin elements
    await expect(page.locator('body')).toContainText('admin');
  });
});
