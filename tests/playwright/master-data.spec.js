import { test, expect } from '@playwright/test';

const BASE  = 'http://127.0.0.1:8000';
const ADMIN = { email: 'dev@gmail.com', password: 'dev12345' };

async function login(page) {
    await page.goto(`${BASE}/login`);
    await page.fill('input[name="email"]', ADMIN.email);
    await page.fill('input[name="password"]', ADMIN.password);
    await page.locator('form button[type="submit"]').click();
    await page.waitForURL(`${BASE}/dashboard`);
}

test.describe('Master Data — Departments', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('departments list loads', async ({ page }) => {
        await page.goto(`${BASE}/master/departments`);
        await expect(page).toHaveTitle(/Department/i);
        await expect(page.locator('table')).toBeVisible();
        const rows = page.locator('tbody tr');
        expect(await rows.count()).toBeGreaterThan(0);
    });

    test('Add modal opens', async ({ page }) => {
        await page.goto(`${BASE}/master/departments`);
        await page.click('button:has-text("Add")');
        await expect(page.locator('#formModal')).toBeVisible();
        await expect(page.locator('#formModalTitle')).toContainText('Add Department');
    });

    test('create department via modal', async ({ page }) => {
        await page.goto(`${BASE}/master/departments`);
        await page.click('button:has-text("Add")');
        await page.locator('#formModal').waitFor({ state: 'visible' });

        const name = `PW Dept ${Date.now()}`;
        await page.fill('#fieldName', name);
        await page.fill('#fieldDescription', 'Created by Playwright');
        // Target save button inside the modal specifically
        await page.locator('#formModal button[type="submit"]').click();

        await expect(page.locator('body')).toContainText(/created successfully|berhasil/i);
        await expect(page.locator('table')).toContainText(name);
    });

    test('Edit modal opens with pre-filled data', async ({ page }) => {
        await page.goto(`${BASE}/master/departments`);
        await page.locator('tbody tr').first().locator('button:has-text("Edit")').click();
        await expect(page.locator('#formModal')).toBeVisible();
        await expect(page.locator('#formModalTitle')).toContainText('Edit Department');
        const nameVal = await page.locator('#fieldName').inputValue();
        expect(nameVal.length).toBeGreaterThan(0);
    });

    test('Delete modal opens with correct item name', async ({ page }) => {
        await page.goto(`${BASE}/master/departments`);
        const firstName = (await page.locator('tbody tr').first().locator('td:nth-child(2)').textContent())?.trim();
        await page.locator('tbody tr').first().locator('button:has-text("Delete")').click();
        await expect(page.locator('#deleteModal')).toBeVisible();
        await expect(page.locator('#deleteItemName')).toContainText(firstName ?? '');
    });

});

test.describe('Master Data — Categories', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('categories list loads', async ({ page }) => {
        await page.goto(`${BASE}/master/categories`);
        await expect(page).toHaveTitle(/Categor/i);
        await expect(page.locator('table')).toBeVisible();
    });

    test('Add modal opens for Category', async ({ page }) => {
        await page.goto(`${BASE}/master/categories`);
        await page.click('button:has-text("Add")');
        await expect(page.locator('#formModal')).toBeVisible();
        await expect(page.locator('#formModalTitle')).toContainText('Add Category');
    });

    test('Edit modal opens with pre-filled data for Category', async ({ page }) => {
        await page.goto(`${BASE}/master/categories`);
        await page.locator('tbody tr').first().locator('button:has-text("Edit")').click();
        await expect(page.locator('#formModal')).toBeVisible();
        await expect(page.locator('#formModalTitle')).toContainText('Edit Category');
        const nameVal = await page.locator('#fieldName').inputValue();
        expect(nameVal.length).toBeGreaterThan(0);
    });

});

test.describe('Master Data — Priorities', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('priorities list loads', async ({ page }) => {
        await page.goto(`${BASE}/master/priorities`);
        await expect(page).toHaveTitle(/Priorit/i);
        await expect(page.locator('table')).toBeVisible();
        expect(await page.locator('tbody tr').count()).toBeGreaterThan(0);
    });

    test('Edit modal populates level field', async ({ page }) => {
        await page.goto(`${BASE}/master/priorities`);
        await page.locator('tbody tr').first().locator('button:has-text("Edit")').click();
        await expect(page.locator('#formModal')).toBeVisible();
        const level = await page.locator('#fieldLevel').inputValue();
        expect(Number(level)).toBeGreaterThan(0);
    });

});

test.describe('Master Data — Statuses', () => {

    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('statuses list loads', async ({ page }) => {
        await page.goto(`${BASE}/master/statuses`);
        await expect(page).toHaveTitle(/Status/i);
        await expect(page.locator('table')).toBeVisible();
        expect(await page.locator('tbody tr').count()).toBeGreaterThan(0);
    });

    test('Edit modal populates name field', async ({ page }) => {
        await page.goto(`${BASE}/master/statuses`);
        await page.locator('tbody tr').first().locator('button:has-text("Edit")').click();
        await expect(page.locator('#formModal')).toBeVisible();
        const name = await page.locator('#fieldName').inputValue();
        expect(name.length).toBeGreaterThan(0);
    });

});
