import { test, expect } from '@playwright/test';

// Shared login helper
async function loginAsAdmin(page) {
    await page.goto('/login');
    await page.getByRole('textbox', { name: 'Email' }).fill('dev@gmail.com');
    await page.getByRole('textbox', { name: 'Password' }).fill('dev12345');
    await page.getByRole('button', { name: 'login Masuk' }).click();
    await page.waitForURL('**/dashboard');
}

// ── AUTH ─────────────────────────────────────────────────────

test('login berhasil sebagai admin', async ({ page }) => {
    await page.goto('/login');
    await page.getByRole('textbox', { name: 'Email' }).fill('dev@gmail.com');
    await page.getByRole('textbox', { name: 'Password' }).fill('dev12345');
    await page.getByRole('button', { name: 'login Masuk' }).click();
    await expect(page).toHaveURL(/dashboard/);
});

test('logout berhasil', async ({ page }) => {
    await loginAsAdmin(page);
    await page.locator('.nav-user-avatar').click(); // buka user dropdown di navbar
    await page.getByRole('button', { name: 'Logout' }).click();
    await expect(page).toHaveURL(/login/);
});

// ── TICKETS ──────────────────────────────────────────────────

test('buat ticket baru', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'Tickets' }).click();
    await page.getByRole('link', { name: 'add Create Ticket' }).click();

    await page.getByRole('textbox', { name: 'Title' }).fill('Komputer Mati');
    await page.getByRole('textbox', { name: 'Description' }).fill('Komputer mati tidak bisa nyala');
    await page.getByLabel('Category').selectOption('1');
    await page.locator('input[name="due_date"]').fill('2026-07-23');
    await page.getByRole('button', { name: 'Save Ticket' }).click();

    await expect(page.getByRole('heading', { name: 'Komputer Mati' })).toBeVisible();
});

test('ubah status ticket', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'Tickets' }).click();
    await page.getByRole('link', { name: 'Komputer Mati' }).first().click();

    await page.locator('select[name="status"]').selectOption('In Progress');
    await page.getByRole('button', { name: 'Set' }).click();

    await expect(page.locator('span.badge').filter({ hasText: 'In Progress' }).first()).toBeVisible();
});

test('assign teknisi ke ticket', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'Tickets' }).click();
    await page.getByRole('link', { name: 'Komputer Mati' }).first().click();

    await page.getByRole('button', { name: 'Assign' }).click();

    await expect(page).toHaveURL(/tickets/);
});

// ── MASTER DATA — USERS ───────────────────────────────────────

test('tambah user baru', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Users' }).click();
    await page.getByRole('button', { name: 'add Add' }).click();

    await page.getByRole('textbox', { name: 'Full name' }).fill('Robert');
    await page.getByRole('textbox', { name: 'name@example.com' }).fill('robert@gmail.com');
    await page.getByRole('textbox', { name: 'Min. 8 characters' }).fill('12345678');
    await page.getByRole('button', { name: 'Save' }).click();

    await expect(page.getByRole('cell', { name: 'Robert', exact: true })).toBeVisible();
});

test('edit nama user', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Users' }).click();

    await page.getByRole('button', { name: 'Edit' }).nth(1).click();
    await page.getByRole('textbox', { name: 'Full name' }).fill('Robert Jr');
    await page.getByRole('button', { name: 'Save' }).click();

    await expect(page.getByText('Robert Jr')).toBeVisible();
});

test('hapus user', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Users' }).click();

    await page.getByRole('button', { name: 'Delete' }).first().click();
    await page.locator('#deleteForm').getByRole('button', { name: 'Delete' }).click();
});

// ── MASTER DATA — DEPARTMENTS ─────────────────────────────────

test('tambah department baru', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Departments' }).click();
    await page.getByRole('button', { name: 'add Add' }).click();

    await page.getByRole('textbox', { name: 'Department name' }).fill('IT Support');
    await page.getByRole('textbox', { name: 'Optional description' }).fill('Divisi IT Support');
    await page.getByRole('button', { name: 'Save' }).click();

    await expect(page.getByRole('cell', { name: 'IT Support', exact: true })).toBeVisible();
});

test('hapus department', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Departments' }).click();

    await page.getByRole('button', { name: 'Delete' }).first().click();
    await page.locator('button').filter({ hasText: /^Delete$/ }).click();
});

// ── MASTER DATA — CATEGORIES ──────────────────────────────────

test('tambah kategori baru', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.getByRole('button', { name: 'add Add' }).click();

    await page.getByRole('textbox', { name: 'Category name' }).fill('Skill Isu');
    await page.locator('#fieldDepartment').selectOption('4');
    await page.locator('#fieldDefaultPriority').selectOption('Low');
    await page.getByRole('textbox', { name: 'Optional description' }).fill('Belum ada info');
    await page.getByRole('button', { name: 'Save' }).click();

    await expect(page.getByText('Skill Isu')).toBeVisible();
});

test('edit kategori', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();

    await page.getByRole('button', { name: 'Edit' }).first().click();
    await page.locator('#fieldDefaultPriority').selectOption('');
    await page.getByRole('button', { name: 'Save' }).click();
});

test('hapus kategori', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();

    await page.getByRole('button', { name: 'Delete' }).first().click();
    await page.locator('button').filter({ hasText: /^Delete$/ }).click();
});

// ── MASTER DATA — PRIORITIES & STATUSES ──────────────────────

test('halaman priorities dapat diakses', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Priorities' }).click();

    await page.getByRole('button', { name: 'Edit' }).first().click();
    await page.getByRole('button', { name: 'Cancel' }).click();

    await expect(page).toHaveURL(/priorities/);
});

test('halaman statuses dapat diakses', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Statuses' }).click();

    await expect(page).toHaveURL(/statuses/);
});

// ── MASTER DATA — TECHNICIANS ─────────────────────────────────

test('assign department ke teknisi', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'inventory_2 Master Data' }).click();
    await page.getByRole('link', { name: 'Technicians' }).click();

    await page.getByRole('checkbox', { name: 'Human Resources' }).check();
    await page.getByRole('button', { name: 'Save' }).click();
});

// ── PROFILE ───────────────────────────────────────────────────

test('halaman profile dapat diakses', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'account_circle Account' }).click();
    await page.getByRole('link', { name: 'Profile' }).click();

    await expect(page).toHaveURL(/profile/);
});

test('halaman active sessions dapat diakses', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'account_circle Account' }).click();
    await page.getByRole('link', { name: 'Profile' }).click();
    await page.getByRole('link', { name: 'devices Active Sessions' }).click();

    await expect(page).toHaveURL(/sessions/);
});

test('halaman login history dapat diakses', async ({ page }) => {
    await loginAsAdmin(page);
    await page.getByRole('link', { name: 'account_circle Account' }).click();
    await page.getByRole('link', { name: 'Profile' }).click();
    await page.getByRole('link', { name: 'history Login History' }).click();

    await expect(page).toHaveURL(/login-history/);
});