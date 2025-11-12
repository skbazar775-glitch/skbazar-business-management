const fs = require('fs');
const path = require('path');

// Get component name and target folder
const componentName = process.argv[2];
const targetDir = process.argv[3] || 'src/components'; // Default folder

if (!componentName) {
  console.error('❌ Please provide a component name.');
  console.log('👉 Example: node create-component.cjs MyComponent src/components/forms');
  process.exit(1);
}

// Construct full path and filename
const fileName = `${componentName}.jsx`;
const folderPath = path.join(__dirname, targetDir);
const filePath = path.join(folderPath, fileName);

// Create the folder if it doesn't exist
if (!fs.existsSync(folderPath)) {
  fs.mkdirSync(folderPath, { recursive: true });
  console.log(`📁 Created folder: ${folderPath}`);
}

// Component template
const componentCode = `import React from 'react';

const ${componentName} = () => {
  return (
    <div>
      <h1>Hello, World from ${componentName}!</h1>
    </div>
  );
};

export default ${componentName};
`;

// Write file
fs.writeFileSync(filePath, componentCode, 'utf8');
console.log(`✅ Component created at ${filePath}`);
