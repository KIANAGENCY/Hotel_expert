const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

(async () => {
  const htmlPath = path.join(__dirname, 'mockup.html');
  const pdfPath = path.join(__dirname, '..', 'Hotel_Expert_Mockup_2026.pdf');
  const downloadsPath = path.join(process.env.USERPROFILE || '', 'Downloads', 'Hotel_Expert_Mockup_2026.pdf');

  if (!fs.existsSync(htmlPath)) {
    console.error('mockup.html not found');
    process.exit(1);
  }

  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
  });

  const page = await browser.newPage();
  await page.goto('file:///' + htmlPath.replace(/\\/g, '/'), {
    waitUntil: 'networkidle0',
    timeout: 60000,
  });

  await page.pdf({
    path: pdfPath,
    format: 'A4',
    landscape: true,
    printBackground: true,
    preferCSSPageSize: true,
    margin: { top: 0, right: 0, bottom: 0, left: 0 },
  });

  await browser.close();

  try {
    fs.copyFileSync(pdfPath, downloadsPath);
    console.log('PDF saved:', pdfPath);
    console.log('Copy:', downloadsPath);
  } catch (e) {
    console.log('PDF saved:', pdfPath);
    console.log('Downloads copy skipped:', e.message);
  }
})();
