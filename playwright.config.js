import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    testDir: './tests/playwright',
    timeout: 30_000,
    retries: 1,
    reporter: [['list'], ['html', { open: 'never', outputFolder: 'tests/playwright/report' }]],
    use: {
        baseURL: 'http://127.0.0.1:8000',
        screenshot: 'only-on-failure',
        video: 'off',
        headless: true,
    },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'] },
        },
    ],
});
