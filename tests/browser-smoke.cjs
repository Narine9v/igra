const { chromium } = require('playwright');
const assert = require('node:assert/strict');
const fs = require('node:fs');
(async () => {
 const browser = await chromium.launch({executablePath:process.env.CHROMIUM_PATH || '/usr/bin/chromium',headless:true,args:['--no-sandbox']});
 const page = await browser.newPage();
 const errors=[];
 page.on('pageerror',e=>errors.push(e.message));
 for (const width of [390,768,1440]) {
  await page.setViewportSize({width,height:900});
  const response = await page.goto('http://127.0.0.1:8080/',{waitUntil:'networkidle'});
  assert.equal(response.status(),200);
  assert.equal(await page.locator('h1').innerText(),'Торжество\nверы и любви');
  assert.equal(await page.locator('main section').count(),8);
  assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),'horizontal overflow at '+width);
  await page.locator('.booking-pending').first().click();
  assert(await page.locator('#booking-dialog').isVisible());
  await page.keyboard.press('Escape');
  assert(!(await page.locator('#booking-dialog').isVisible()));
  if(width===390) {
   await page.locator('.menu-toggle').click();
   assert.equal(await page.locator('.menu-toggle').getAttribute('aria-expanded'),'true');
   await page.locator('#navigation a').first().click();
   assert.equal(await page.locator('.menu-toggle').getAttribute('aria-expanded'),'false');
  }
  fs.mkdirSync('test-results',{recursive:true});
  await page.screenshot({path:`test-results/home-${width}.png`,fullPage:true});
  console.log('PASS: viewport',width,'sections, registration notice, overflow');
 }
 await page.goto('http://127.0.0.1:8080/');
 const story=page.getByRole('link',{name:'Проверка редактирования новости',exact:true});
 assert.equal(await story.count(),1,'published edited news appears');
 await story.click();
 assert.equal(await page.locator('h1').innerText(),'Проверка редактирования новости');
 console.log('PASS: published news visible, article opens');
 assert.deepEqual(errors,[]);
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
