const puppeteer = require('puppeteer');
const { PDFDocument, rgb, StandardFonts } = require('pdf-lib');
const fs = require('fs');
const path = require('path');

const BASE = 'http://localhost/hotel_expert';
const OUT_DIR = path.join(__dirname, 'screenshots');
const PDF_OUT = path.join(__dirname, '..', 'Hotel_Expert_Capturas_2026.pdf');
const PDF_DOWNLOADS = path.join(process.env.USERPROFILE || '', 'Downloads', 'Hotel_Expert_Capturas_2026.pdf');

const VIEWPORT = { width: 1440, height: 900, deviceScaleFactor: 1 };

const PAGES = [
  { title: 'Home — Inicio', url: '/index.php' },
  { title: 'Catálogo B2B', url: '/catalogo.php' },
  { title: 'Producto — Concentrado Estándar', url: '/producto.php?slug=estandar' },
  { title: 'Producto — Hotel Expert Dual', url: '/producto.php?slug=dual' },
  { title: 'Cómo funciona — Manual de dilución', url: '/como-funciona.php' },
  { title: 'Rastreo de pedido', url: '/rastreo.php' },
  { title: 'Sobre nosotros', url: '/nosotros.php' },
  { title: 'Blog y recursos', url: '/blog.php' },
  { title: 'Contacto y cotización', url: '/contacto.php' },
];

async function waitForPageReady(page) {
  await page.waitForNetworkIdle({ idleTime: 500, timeout: 30000 }).catch(() => {});
  await page.evaluate(async () => {
    if (document.fonts?.ready) await document.fonts.ready;
  });
  await new Promise((r) => setTimeout(r, 800));
}

async function capturePageSections(page, slug) {
  const totalHeight = await page.evaluate(() =>
    Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
  );
  const shots = [];
  const step = VIEWPORT.height;
  let y = 0;
  let index = 0;

  while (y < totalHeight) {
    await page.evaluate((scrollY) => window.scrollTo(0, scrollY), y);
    await new Promise((r) => setTimeout(r, 400));

    const remaining = totalHeight - y;
    const clipHeight = Math.min(step, remaining);

    const file = path.join(OUT_DIR, `${slug}-${String(index).padStart(2, '0')}.png`);
    await page.screenshot({
      path: file,
      type: 'png',
      clip: { x: 0, y: 0, width: VIEWPORT.width, height: clipHeight },
    });
    shots.push({ file, height: clipHeight });
    y += step;
    index++;
  }
  return shots;
}

async function addCoverPage(pdf, fontBold, fontReg) {
  const page = pdf.addPage([842, 595]);
  const { width, height } = page.getSize();

  page.drawRectangle({
    x: 0,
    y: 0,
    width,
    height,
    color: rgb(0.043, 0.137, 0.271),
  });

  page.drawRectangle({
    x: width * 0.55,
    y: 0,
    width: width * 0.45,
    height,
    color: rgb(0, 0.549, 0.584),
    opacity: 0.35,
  });

  page.drawText('HOTEL EXPERT', {
    x: 48,
    y: height - 72,
    size: 14,
    font: fontBold,
    color: rgb(0.322, 0.784, 0.784),
  });

  page.drawText('Mockup con capturas reales', {
    x: 48,
    y: height - 130,
    size: 32,
    font: fontBold,
    color: rgb(1, 1, 1),
    maxWidth: 480,
    lineHeight: 36,
  });

  page.drawText('Frescura que se siente. Marca que se recuerda.', {
    x: 48,
    y: height - 175,
    size: 13,
    font: fontReg,
    color: rgb(0.85, 0.92, 0.92),
    maxWidth: 420,
  });

  page.drawText(`Generado: ${new Date().toLocaleDateString('es-MX', { year: 'numeric', month: 'long', day: 'numeric' })}`, {
    x: 48,
    y: 48,
    size: 10,
    font: fontReg,
    color: rgb(0.7, 0.8, 0.8),
  });

  page.drawText('localhost/hotel_expert · HTML · CSS · JS · PHP · Tailwind', {
    x: 48,
    y: 32,
    size: 9,
    font: fontReg,
    color: rgb(0.55, 0.65, 0.65),
  });
}

async function addSectionPage(pdf, title, url, fontBold, fontReg) {
  const page = pdf.addPage([842, 595]);
  const { width, height } = page.getSize();

  page.drawRectangle({ x: 0, y: 0, width, height, color: rgb(0.918, 0.961, 0.961) });
  page.drawRectangle({ x: 0, y: height - 6, width, height: 6, color: rgb(0, 0.549, 0.584) });

  page.drawText(title.toUpperCase(), {
    x: 48,
    y: height / 2 + 20,
    size: 22,
    font: fontBold,
    color: rgb(0.043, 0.137, 0.271),
    maxWidth: width - 96,
  });

  page.drawText(BASE + url, {
    x: 48,
    y: height / 2 - 16,
    size: 11,
    font: fontReg,
    color: rgb(0, 0.549, 0.584),
  });
}

async function addScreenshotPage(pdf, pngBytes, clipHeight) {
  const png = await pdf.embedPng(pngBytes);
  const page = pdf.addPage([842, 595]);
  const { width, height } = page.getSize();
  const margin = 24;
  const maxW = width - margin * 2;
  const maxH = height - margin * 2;

  const scale = Math.min(maxW / png.width, maxH / png.height);
  const drawW = png.width * scale;
  const drawH = png.height * scale;

  page.drawImage(png, {
    x: (width - drawW) / 2,
    y: (height - drawH) / 2,
    width: drawW,
    height: drawH,
  });

  page.drawRectangle({
    x: margin - 1,
    y: margin - 1,
    width: drawW + 2,
    height: drawH + 2,
    borderColor: rgb(0.85, 0.88, 0.88),
    borderWidth: 1,
  });
}

(async () => {
  if (fs.existsSync(OUT_DIR)) {
    fs.rmSync(OUT_DIR, { recursive: true });
  }
  fs.mkdirSync(OUT_DIR, { recursive: true });

  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
  });

  const page = await browser.newPage();
  await page.setViewport(VIEWPORT);

  const pdf = await PDFDocument.create();
  const fontBold = await pdf.embedFont(StandardFonts.HelveticaBold);
  const fontReg = await pdf.embedFont(StandardFonts.Helvetica);

  await addCoverPage(pdf, fontBold, fontReg);

  for (const entry of PAGES) {
    const slug = entry.url.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '').toLowerCase();
    console.log('Capturing:', entry.title);

    await page.goto(BASE + entry.url, { waitUntil: 'networkidle0', timeout: 60000 });
    await waitForPageReady(page);

    await page.evaluate(() => {
      document.querySelectorAll('.reveal, .io-reveal').forEach((el) => {
        el.style.opacity = '1';
        el.style.transform = 'none';
        el.style.animation = 'none';
      });
      window.scrollTo(0, 0);
    });

    const shots = await capturePageSections(page, slug);
    await addSectionPage(pdf, entry.title, entry.url, fontBold, fontReg);

    for (const shot of shots) {
      const bytes = fs.readFileSync(shot.file);
      await addScreenshotPage(pdf, bytes, shot.height);
    }
  }

  await browser.close();

  const pdfBytes = await pdf.save();
  fs.writeFileSync(PDF_OUT, pdfBytes);

  try {
    fs.copyFileSync(PDF_OUT, PDF_DOWNLOADS);
  } catch (_) {}

  const sizeMb = (pdfBytes.length / 1024 / 1024).toFixed(2);
  console.log(`PDF listo: ${PDF_OUT} (${sizeMb} MB)`);
  console.log(`Copia: ${PDF_DOWNLOADS}`);
  console.log(`Capturas en: ${OUT_DIR}`);
})();
