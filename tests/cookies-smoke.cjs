const {chromium}=require('playwright');
const assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:'/usr/bin/chromium',args:['--no-sandbox']});
 try{
  const page=await browser.newPage();
  for(const width of [320,390,1440]){
   await page.setViewportSize({width,height:900});
   await page.goto('http://127.0.0.1:8080/');
   await page.evaluate(()=>localStorage.clear());await page.reload();
   assert(await page.locator('#cookie-notice').isVisible());
   assert(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth));
   await page.locator('#cookie-notice [data-cookie-details]').click();
   assert(await page.locator('#cookie-details').isVisible());
   await page.keyboard.press('Escape');
   await page.locator('#cookie-dismiss').click();
   assert(!(await page.locator('#cookie-notice').isVisible()));
   await page.reload();assert(!(await page.locator('#cookie-notice').isVisible()));
   await page.locator('.site-footer [data-cookie-details]').click();
   assert(await page.locator('#cookie-details').isVisible());await page.keyboard.press('Escape');
   console.log('PASS: cookie notice, persistence and details at',width);
  }
  await page.evaluate(()=>localStorage.setItem('zv-cookie-notice-v1',JSON.stringify({version:1,at:Date.now()-181*86400000})));
  await page.reload();assert(await page.locator('#cookie-notice').isVisible());
  await page.evaluate(()=>localStorage.setItem('zv-cookie-notice-v1','invalid-json'));
  await page.reload();assert(await page.locator('#cookie-notice').isVisible());
  const blocked=await browser.newPage();
  await blocked.addInitScript(()=>{Object.defineProperty(window,'localStorage',{get(){throw Error('Storage unavailable')}})});
  await blocked.goto('http://127.0.0.1:8080/');
  await blocked.locator('#cookie-dismiss').click();assert(!(await blocked.locator('#cookie-notice').isVisible()));
  console.log('PASS: expiry, corrupted data, unavailable storage');
 }finally{await browser.close()}
})().catch(e=>{console.error(e);process.exit(1)});
