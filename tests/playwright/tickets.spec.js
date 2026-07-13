import { test, expect } from '@playwright/test';

const BASE  = 'http://127.0.0.1:8000';
const ADMIN = { email: 'dev@gmail.com', password: 'dev12345' };

async function login(page, creds = ADMIN) {
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', creds.email);
    await page.fill('input[name="password"]', creds.password);
    // Target the login form's submit specifically
    await page.locator('form button[type="submit"]').click();
    await page.waitForURL(`${BASE}/dashboard`);
}

test.describe('Ticket List', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('ticket list page loads', async ({ page }) => {
        await page.goto(`${BASE}/tickets`);
        await expect(page).toHaveTitle(/Ticket/i);
        await expect(page.locator('table')).toBeVisible();
    });

    test('ticket list shows data rows', async ({ page }) => {
        await page.goto(`${BASE}/tickets`);
        const rows = page.locator('tbody tr');
        await expect(rows.first()).toBeVisible();
        const count = await rows.count();
        expect(count).toBeGreaterThan(0);
    });

    test('search filter works', async ({ page }) => {
        await page.goto(`${BASE}/tickets`);
        await page.fill('input[name="search"]', 'Internet');
        // Use Enter key — avoids targeting the wrong submit button
        await page.locator('input[name="search"]').press('Enter');
        await page.waitForLoadState('networkidle');
        await expect(page).toHaveURL(/search=Internet/);
        await expect(page.locator('tbody')).toBeVisible();
    });

    test('create ticket button visible', async ({ page }) => {
        await page.goto(`${BASE}/tickets`);
        await expect(page.locator('a:has-text("Create Ticket")')).toBeVisible();
    });

    test('view ticket detail from list', async ({ page }) => {
        await page.goto(`${BASE}/tickets`);
        const viewBtn = page.locator('tbody tr').first().locator('a[title="View"]');
        await viewBtn.click();
        await expect(page).toHaveURL(/tickets\/\d+/);
    });

});

test.describe('Ticket Create', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('create ticket form renders', async ({ page }) => {
        await page.goto(`${BASE}/tickets/create`);
        await expect(page.locator('input[name="title"]')).toBeVisible();
        await expect(page.locator('textarea[name="description"]')).toBeVisible();
        await expect(page.locator('select[name="priority"]')).toBeVisible();
        await expect(page.locator('button:has-text("Save Ticket")')).toBeVisible();
    });

    test('create ticket with valid data', async ({ page }) => {
        await page.goto(`${BASE}/tickets/create`);

        const title = `Playwright Test Ticket ${Date.now()}`;
        await page.fill('input[name="title"]', title);
        await page.fill('textarea[name="description"]', 'Created by Playwright automated test');
        await page.selectOption('select[name="priority"]', 'Medium');

        // Target the Save Ticket button specifically by text
        await page.locator('button:has-text("Save Ticket")').click();
        await expect(page).toHaveURL(/tickets\/\d+/);
        await expect(page.locator('body')).toContainText(/berhasil|success/i);
    });

    test('create ticket shows validation error without priority', async ({ page }) => {
        await page.goto(`${BASE}/tickets/create`);
        await page.fill('input[name="title"]', 'Test Title');
        await page.fill('textarea[name="description"]', 'Test Description');
        // Leave priority empty — bypass HTML5 required so server validates
        await page.evaluate(() => {
            document.querySelectorAll('select[name="priority"] option[disabled]').forEach(o => o.removeAttribute('disabled'));
            document.querySelector('select[name="priority"]').removeAttribute('required');
            document.querySelector('select[name="priority"]').value = '';
        });
        await page.locator('button:has-text("Save Ticket")').click();
        await page.waitForLoadState('networkidle');
        // Server returns 422 and redirects back with validation errors
        await expect(page.locator('body')).toContainText(/Prioritas|required|wajib/i);
    });

});
