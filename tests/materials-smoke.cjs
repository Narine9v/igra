const {chromium}=require('playwright');
const {execFileSync}=require('node:child_process');
const assert=require('node:assert/strict');
(async()=>{
 const browser=await chromium.launch({executablePath:process.env.CHROMIUM_PATH||'/usr/bin/chromium',args:['--no-sandbox']});
 try {
  const page=await browser.newPage();
  await page.goto('http://127.0.0.1:8080/wp-login.php');
  await page.locator('#user_login').fill('local_editor');
  const password=execFileSync('docker',['compose','exec','-T','wordpress','cat','/var/www/html/.local-admin-password'],{encoding:'utf8'}).trim();
  await page.locator('#user_pass').fill(password);
  await page.locator('#wp-submit').click();
  await page.waitForURL('**/wp-admin/');
  async function openMaterial(){
   await page.goto('http://127.0.0.1:8080/wp-admin/tools.php?page=zv-materials');
   await page.getByRole('button',{name:'Открыть черновик новости'}).click();
   await page.waitForURL(/post\.php\?post=\d+&action=edit/);
   return new URL(page.url()).searchParams.get('post');
  }
  const id=await openMaterial();
  await page.waitForFunction(()=>window.wp?.data?.select('core/editor')?.getCurrentPost()?.title);
  const post=await page.evaluate(()=>window.wp.data.select('core/editor').getCurrentPost());
  assert.equal(post.status,'draft');
  assert.equal(post.title,'🌟 Готовимся к Рождественским встречам!');
  assert(post.content.includes('https://max.ru/id2310223402_biz'));
  assert(post.content.includes('tel:+79898200400'));
  await page.evaluate(async()=>{window.wp.data.dispatch('core/editor').editPost({title:'Проверка сохранения редакторских правок'});await window.wp.data.dispatch('core/editor').savePost();});
  assert.equal(await openMaterial(),id,'repeat button reuses existing draft');
  await page.waitForFunction(()=>window.wp?.data?.select('core/editor')?.getCurrentPost()?.title==='Проверка сохранения редакторских правок');
  console.log('PASS: one-click draft, links, repeat import, editorial changes preserved');
 } finally { await browser.close(); }
})().catch(e=>{console.error(e.message);process.exit(1)});
