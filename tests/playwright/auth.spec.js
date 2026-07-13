import { test, expect } from '@playwright/test';

const BASE  = 'http://127.0.0.1:8000';
const ADMIN = { email: 'dev@gmail.com', password: 'dev12345' };

async function login(page, creds = ADMIN) {
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', creds.email);
    await page.fill('input[name="password"]', creds.password);
    await page.locator('form button[type="submit"]').click();
    await page.waitForURL(`${BASE}/dashboard`);
}

test.describe('Authentication', () => {

    test('login page renders correctly', async ({ page }) => {
        await page.goto(`${BASE}/login`);
        await expect(page).toHaveTitle(/Login|IT Helpdesk/i);
        await expect(page.locator('input[name="email"]')).toBeVisible();
        await expect(page.locator('input[name="password"]')).toBeVisible();
        await expect(page.locator('form button[type="submit"]')).toBeVisible();
    });

    test('login with valid admin credentials', async ({ page }) => {
        await page.goto(`${BASE}/login`);
        await page.fill('input[name="email"]', ADMIN.email);
        await page.fill('input[name="password"]', ADMIN.password);
        await page.locator('form button[type="submit"]').click();
        await expect(page).toHaveURL(`${BASE}/dashboard`);
    });

    test('login with wrong password shows error', async ({ page }) => {
        await page.goto(`${BASE}/login`);
        await page.fill('input[name="email"]', ADMIN.email);
        await page.fill('input[name="password"]', 'wrongpassword');
        await page.locator('form button[type="submit"]').click();
        await expect(page).toHaveURL(/login/);
        await expect(page.locator('body')).toContainText(/password|credentials|These credentials/i);
    });

    test('unauthenticated redirect to login', async ({ page }) => {
        await page.goto(`${BASE}/dashboard`);
        await expect(page).toHaveURL(/login/);
    });

    test('logout works', async ({ page }) => {
        await login(page);
        // Logout form in sidebar
        await page.locator('form[action*="logout"] button').first().click();
        await expect(page).toHaveURL(/login|\//);
    });

});
