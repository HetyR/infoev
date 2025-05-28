const { chromium } = require('playwright');

async function checkTokopediaProduct(url) {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();

  try {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });

    // Tunggu elemen utama produk muncul, timeout 10 detik
    await page.waitForSelector('div[data-testid="pdpMainContent"]', { timeout: 10000 });

    // Cek tanda-tanda produk tidak aktif
    const pageText = (await page.textContent('body')).toLowerCase();

    const invalidKeywords = [
      'produk tidak tersedia',
      'produk sudah tidak tersedia',
      'habis',
      'tidak ditemukan',
      'produk habis',
      'produk diarsipkan',
      'halaman tidak ditemukan'
    ];

    for (const keyword of invalidKeywords) {
      if (pageText.includes(keyword)) {
        console.log('inactive');
        await browser.close();
        return;
      }
    }

    console.log('active');
  } catch (error) {
    console.error('Error checking Tokopedia:', error.message);
    console.log('inactive');
  } finally {
    await browser.close();
  }
}

const url = process.argv[2];
if (!url) {
  console.log('Usage: node checkTokopedia.js <url>');
  process.exit(1);
}

checkTokopediaProduct(url);
