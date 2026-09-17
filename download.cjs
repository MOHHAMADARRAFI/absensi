const https = require('https');
const fs = require('fs');

const options = {
  hostname: 'upload.wikimedia.org',
  port: 443,
  path: '/wikipedia/commons/6/69/LAMBANG_KABUPATEN_KARAWANG.svg',
  method: 'GET',
  headers: {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
  }
};

const req = https.request(options, res => {
  res.pipe(fs.createWriteStream('public/img/logo-karawang.svg'));
});
req.end();
