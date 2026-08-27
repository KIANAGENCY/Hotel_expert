const puppeteer = require('puppeteer');
const { PDFDocument, rgb, StandardFonts } = require('pdf-lib');
const fs = require('fs');
const path = require('path');

const BASE = 'http://hotel_expert.test';
const OUT_DIR = path.join(__dirname, 'screenshots');
const PDF_OUT = path.join(__dirname, '..', 'Hotel_Expert_Sistema_ELAH_Mockup.pdf');
const VIEWPORT = { width: 1440, height: 900, deviceScaleFactor: 1 };

const PAGES = [
  { title: 'Inicio — Sistema ELAH', url: '/', slug: 'inicio' },
  { title: 'Sistema ELAH', url: '/sistema-elah/', slug: 'sistema-elah' },
  { title: 'Productos', url: '/productos/', slug: 'productos' },
  { title: 'Hotel Expert', url: '/productos/hotel-expert/', slug: 'hotel-expert' },
  { title: 'Hotel Expert Dual', url: '/productos/hotel-expert-dual/', slug: 'hotel-expert-dual' },
  { title: 'Aroma insignia', url: '/aroma-insignia/', slug: 'aroma-insignia' },
  { title: 'Recursos', url: '/recursos/', slug: 'recursos' },
  { title: 'Manual de uso', url: '/manual-de-uso/', slug: 'manual-de-uso' },
  { title: 'Nosotros', url: '/nosotros/', slug: 'nosotros' },
  { title: 'Contacto', url: '/contacto/', slug: 'contacto' },
];

async function waitForPageReady(page) {
  await page.waitForNetworkIdle({ idleTime: 500, timeout: 30000 }).catch(() => {});
  await page.evaluate(async () => {
    if (document.fonts?.ready) await document.fonts.ready;
    document.querySelectorAll('.reveal, .io-reveal, .text-reveal').forEach((element) => {
      element.style.opacity = '1';
      element.style.transform = 'none';
      element.style.filter = 'none';
      element.style.animation = 'none';
      element.style.transition = 'none';
    });
    window.scrollTo(0, 0);
  });
  await new Promise((resolve) => setTimeout(resolve, 500));
}

async function addCover(pdf, fontBold, fontRegular) {
  const page = pdf.addPage([842, 595]);
  page.drawRectangle({ x: 0, y: 0, width: 842, height: 595, color: rgb(0.043, 0.137, 0.271) });
  page.drawRectangle({ x: 500, y: 0, width: 342, height: 595, color: rgb(0, 0.549, 0.584), opacity: 0.35 });
  page.drawText('HOTEL EXPERT', { x: 48, y: 520, size: 14, font: fontBold, color: rgb(0.322, 0.784, 0.784) });
  page.drawText('Sistema ELAH', { x: 48, y: 454, size: 34, font: fontBold, color: rgb(1, 1, 1) });
  page.drawText('Mock-up integral del sitio web', { x: 48, y: 420, size: 15, font: fontRegular, color: rgb(0.85, 0.92, 0.92) });
  page.drawText('Una captura completa por página · sin duplicados', { x: 48, y: 392, size: 11, font: fontRegular, color: rgb(0.7, 0.82, 0.82) });
  page.drawText(`Generado: ${new Date().toLocaleDateString('es-MX')}`, { x: 48, y: 42, size: 10, font: fontRegular, color: rgb(0.7, 0.8, 0.8) });
}

async function addFullPageMockup(pdf, imageBytes, entry, fontBold, fontRegular) {
  const image = await pdf.embedPng(imageBytes);
  const pageWidth = 842;
  const margin = 24;
  const imageWidth = pageWidth - margin * 2;
  const imageHeight = image.height * (imageWidth / image.width);
  const page = pdf.addPage([pageWidth, imageHeight + 92]);
  const { height } = page.getSize();

  page.drawRectangle({ x: 0, y: 0, width: pageWidth, height, color: rgb(0.965, 0.975, 0.975) });
  page.drawText(entry.title, { x: 24, y: height - 30, size: 14, font: fontBold, color: rgb(0.043, 0.137, 0.271) });
  page.drawText(BASE + entry.url, { x: 24, y: height - 48, size: 8, font: fontRegular, color: rgb(0, 0.549, 0.584) });
  page.drawImage(image, { x: margin, y: 24, width: imageWidth, height: imageHeight });
  page.drawRectangle({ x: 23, y: 23, width: imageWidth + 2, height: imageHeight + 2, borderColor: rgb(0.83, 0.87, 0.87), borderWidth: 1 });
}

(async () => {
  fs.mkdirSync(OUT_DIR, { recursive: true });
  for (const file of fs.readdirSync(OUT_DIR)) {
    if (file.endsWith('.png')) fs.unlinkSync(path.join(OUT_DIR, file));
  }

  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
  });
  const page = await browser.newPage();
  await page.setViewport(VIEWPORT);

  const pdf = await PDFDocument.create();
  const fontBold = await pdf.embedFont(StandardFonts.HelveticaBold);
  const fontRegular = await pdf.embedFont(StandardFonts.Helvetica);
  await addCover(pdf, fontBold, fontRegular);

  for (const entry of PAGES) {
    console.log(`Capturando: ${entry.title}`);
    const response = await page.goto(BASE + entry.url, { waitUntil: 'networkidle0', timeout: 60000 });
    if (!response || !response.ok()) throw new Error(`No se pudo abrir ${entry.url}`);
    await waitForPageReady(page);

    const screenshotPath = path.join(OUT_DIR, `${entry.slug}.png`);
    await page.screenshot({ path: screenshotPath, type: 'png', fullPage: true });
    await addFullPageMockup(pdf, fs.readFileSync(screenshotPath), entry, fontBold, fontRegular);
  }

  await browser.close();
  const bytes = await pdf.save();
  fs.writeFileSync(PDF_OUT, bytes);
  console.log(`PDF listo: ${PDF_OUT}`);
  console.log(`Páginas del PDF: ${PAGES.length + 1} (portada + ${PAGES.length} capturas únicas)`);
})().catch((error) => {
  console.error(error);
  process.exit(1);
});
