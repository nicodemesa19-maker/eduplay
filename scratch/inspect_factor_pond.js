const fs = require('fs');
const filePath = 'resources/views/games/factor_pond.blade.php';
const content = fs.readFileSync(filePath, 'utf8');

console.log('Total characters:', content.length);
console.log('Total lines:', content.split('\n').length);
