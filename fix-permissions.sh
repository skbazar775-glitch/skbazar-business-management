#!/bin/bash
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✅ Permissions fixed: files=644, folders=755"
