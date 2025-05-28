const { chromium } = require('playwright');

async function checkShopeeProduct(url) {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({ viewport: { width: 1280, height: 720 } });
  const page = await context.newPage();

  try {
    const response = await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
    if (!response || response.status() === 404) {
      console.log('not_found');
      return 'not_found';
    }

    const pageText = (await page.textContent('body')).toLowerCase();
    console.log(`Page text snippet: ${pageText.substring(0, 200)}`);

    if (pageText.includes('stok habis') || pageText.includes('produk habis')) {
      console.log('out_of_stock');
      return 'out_of_stock';
    }
    if (pageText.includes('produk tidak ditemukan') || pageText.includes('halaman tidak ditemukan')) {
      console.log('not_found');
      return 'not_found';
    }
    if (pageText.includes('diarsipkan') || pageText.includes('produk diarsipkan')) {
      console.log('archived');
      return 'archived';
    }

    const buyButton = await page.$('button.btn-solid-primary:not(.btn-solid-primary--disabled)', { timeout: 15000 });
    if (buyButton) {
      console.log('active');
      return 'active';
    } else {
      console.log('inactive');
      return 'inactive';
    }
  } catch (err) {
    console.error(`Error checking ${url}:`, err.message);
    console.log('error');
    return 'error';
  } finally {
    await browser.close();
  }
}

const [,, url] = process.argv;
if (!url) {
  console.log('Usage: node checkShopee.js <url>');
  process.exit(1);
}

checkShopeeProduct(url);