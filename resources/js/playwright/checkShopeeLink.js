const { chromium } = require('playwright');

(async () => {
    const url = process.argv[2];

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    try {
        await page.goto(url, {
            waitUntil: 'domcontentloaded',
            timeout: 20000  // 20 detik saja
        });

        // Jika halaman terlalu lama, hentikan saja
        const notExistText = await page.locator('.product-not-exist__text').first().textContent().catch(() => null);

        if (notExistText && notExistText.includes('Produk tidak ada')) {
            console.log('inactive');
        } else {
            console.log('active');
        }

    } catch (e) {
        console.error('Error accessing URL:', e.message);
        console.log('inactive');
    } finally {
        await browser.close();
    }
})();
