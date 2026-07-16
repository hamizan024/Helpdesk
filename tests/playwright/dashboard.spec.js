import { test, expect } from '@playwright/test';

const BASE  = 'http://127.0.0.1:8000';
const ADMIN = { email: 'dev@gmail.com', password: 'dev12345' };

async function login(page, creds = ADMIN) {
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', creds.email);
    await page.fill('input[name="password"]', creds.password);
    await page.click('button[type="submit"]');
    await page.waitForURL(`${BASE}/dashboard`);
}

test.describe('Dashboard', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('dashboard renders stat cards', async ({ page }) => {
        await expect(page).toHaveTitle(/Dashboard/i);
        await expect(page.locator('.card').first()).toBeVisible();
    });

    test('sidebar navigation links present', async ({ page }) => {
        await expect(page.locator('a[href*="tickets"]').first()).toBeVisible();
        await expect(page.locator('a[href*="dashboard"]').first()).toBeVisible();
    });

    test('admin sees Master Data links in sidebar', async ({ page }) => {
        // Master Data is a collapsible sidebar group — expand it first
        await page.click('a[data-bs-target="#masterDataSubmenu"]');
        await page.waitForSelector('#masterDataSubmenu.show');
        await expect(page.locator('a[href*="master/departments"]')).toBeVisible();
        await expect(page.locator('a[href*="master/categories"]')).toBeVisible();
        await expect(page.locator('a[href*="master/priorities"]')).toBeVisible();
        await expect(page.locator('a[href*="master/statuses"]')).toBeVisible();
    });

    test('notification bell visible', async ({ page }) => {
        await expect(page.locator('.material-icons-round:text("notifications")')).toBeVisible();
    });

    test('root URL redirects to dashboard when logged in', async ({ page }) => {
        await page.goto(`${BASE}/`);
        await expect(page).toHaveURL(`${BASE}/dashboard`);
    });

});
