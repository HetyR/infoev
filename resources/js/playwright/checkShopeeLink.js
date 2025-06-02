const { chromium } = require('playwright-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');

chromium.use(StealthPlugin());

(async () => {
  const url = process.argv[2];
  let browser, context, page;

  try {
    // Inisialisasi browser
    browser = await chromium.launch({
      headless: true, // Ubah ke false untuk debugging
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-blink-features=AutomationControlled',
        '--disable-web-security',
      ],
      // proxy: { server: 'http://your-proxy-server:port' }, // Tambahkan proxy jika memungkinkan
    });

    context = await browser.newContext({
      viewport: { width: 1280, height: 720 },
      userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
      bypassCSP: true,
    });

    page = await context.newPage();

    await page.setExtraHTTPHeaders({
      'Accept-Language': 'en-US,en;q=0.9',
      'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    });

    // Navigasi ke URL dengan event yang lebih ringan
    await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });

    // Debugging: Cetak URL akhir
    const finalUrl = await page.url();
    console.log('URL akhir:', finalUrl);

    // Periksa redirect ke halaman login, CAPTCHA, atau error
    if (finalUrl.includes('login') || finalUrl.includes('captcha') || finalUrl.includes('error_page')) {
      console.log('Redirect ke halaman login/captcha/error terdeteksi');
      console.log('inactive');
    } else {
      // Tunggu elemen produk tidak ada muncul (maksimal 30 detik)
      await page.waitForSelector('.product-not-exist__text', { state: 'visible', timeout: 30000 }).catch(() => null);

      // Debugging: Simpan HTML halaman
      const pageContent = await page.content();
      console.log('=== PAGE HTML START ===');
      console.log(pageContent.substring(0, 1000));
      console.log('=== PAGE HTML END ===');

      // Debugging: Simpan screenshot
      await page.screenshot({ path: `screenshot_${Date.now()}.png` });

      // Periksa elemen produk tidak ada
      const productNotExist = await page.locator('.product-not-exist__text').first();
      if (await productNotExist.isVisible()) {
        const text = await productNotExist.textContent();
        console.log('Elemen ditemukan:', text);
        if (text && text.includes('Produk tidak ada')) {
          console.log('inactive');
        }
      }

      // Periksa teks "Produk tidak ada" di halaman secara langsung
      const pageText = await page.evaluate(() => document.body.innerText);
      if (pageText.includes('Produk tidak ada')) {
        console.log('Teks "Produk tidak ada" ditemukan di halaman');
        console.log('inactive');
      }

      // Periksa elemen produk (sebagai konfirmasi bahwa produk ada)
offee      const productTitle = await page.locator('.product-briefing h1').first().catch(() => null);
      if (productTitle && (await productTitle.isVisible())) {
        console.log('Produk ditemukan:', await productTitle.textContent());
        console.log('active');
      } else {
        console.log('Tidak ada elemen produk atau error yang jelas, anggap inactive');
        console.log('inactive');
      }
    }

  } catch (e) {
    console.error('Error saat load page:', e.message);
    if (page) {
      try {
        await page.screenshot({ path: `error_screenshot_${Date.now()}.png` });
      } catch (screenshotErr) {
        console.error('Gagal menyimpan screenshot:', screenshotErr.message);
      }
    }
    console.log('inactive');
  } finally {
    try {
      if (browser) {
        await browser.close();
      }
    } catch (closeErr) {
      console.error('Gagal menutup browser:', closeErr.message);
    }
  }
})();