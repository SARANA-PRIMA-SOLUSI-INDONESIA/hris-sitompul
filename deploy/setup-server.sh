#!/usr/bin/env bash
#
# Setup server VPS untuk hris-sitompul (Laravel 13 + Filament 5)
# Jalankan sebagai root:  sudo bash setup-server.sh
#
set -euo pipefail

echo "===== [1/8] Update sistem ====="
export DEBIAN_FRONTEND=noninteractive
apt-get update -y
apt-get upgrade -y

echo "===== [2/8] Install paket dasar ====="
apt-get install -y software-properties-common curl wget git unzip zip \
    nginx mysql-server supervisor certbot python3-certbot-nginx

echo "===== [3/8] Install PHP 8.3 + ekstensi ====="
add-apt-repository -y ppa:ondrej/php
apt-get update -y
apt-get install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-zip php8.3-gd php8.3-intl php8.3-bcmath php8.3-exif \
    php8.3-fileinfo php8.3-common php8.3-opcache php8.3-dom php8.3-simplexml \
    php8.3-iconv php8.3-mysqlnd php8.3-redis

echo "===== [4/8] Install Composer ====="
if [ ! -f /usr/local/bin/composer ]; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi
composer --version

echo "===== [5/8] Install Node.js 20 ====="
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi
node --version
npm --version

echo "===== [6/8] Buat user deploy (passwordless sudo) ====="
if ! id "deploy" &> /dev/null; then
    useradd -m -s /bin/bash deploy
    echo "deploy ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/deploy
    chmod 440 /etc/sudoers.d/deploy
fi

echo "===== [7/8] Direktori project ====="
mkdir -p /var/www/hris-sitombung
chown -R deploy:www-data /var/www/hris-sitombung
chmod -R 775 /var/www/hris-sitombung

echo "===== [8/8] Konfigurasi PHP production ====="
sed -i 's/^memory_limit.*/memory_limit = 256M/' /etc/php/8.3/cli/php.ini
sed -i 's/^upload_max_filesize.*/upload_max_filesize = 20M/' /etc/php/8.3/fpm/php.ini
sed -i 's/^post_max_size.*/post_max_size = 22M/' /etc/php/8.3/fpm/php.ini
systemctl restart php8.3-fpm

echo ""
echo "======================================================"
echo " Setup server SELESAI!"
echo "======================================================"
echo ""
echo "Langkah berikutnya (manual, satu kali):"
echo ""
echo "  1. Clone repository:"
echo "     su - deploy"
echo "     cd /var/www/hris-sitombung"
echo "     git clone https://github.com/SARANA-PRIMA-SOLUSI-INDONESIA/hris-sitombung.git ."
echo ""
echo "  2. Buat database MySQL:"
echo "     sudo mysql -uroot -p -e \"CREATE DATABASE hris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\""
echo "     sudo mysql -uroot -p -e \"CREATE USER 'hris_user'@'localhost' IDENTIFIED BY 'PASSWORD_KUAT';\""
echo "     sudo mysql -uroot -p -e \"GRANT ALL PRIVILEGES ON hris_db.* TO 'hris_user'@'localhost'; FLUSH PRIVILEGES;\""
echo ""
echo "  3. Buat .env & install:"
echo "     cp .env.example .env && nano .env"
echo "     composer install --no-dev --optimize-autoloader"
echo "     npm install && npm run build"
echo "     php artisan key:generate"
echo "     php artisan migrate --force --seed"
echo "     php artisan storage:link"
echo ""
echo "  4. SSH key untuk GitHub Actions:"
echo "     ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -N ''"
echo "     cat ~/.ssh/id_ed25519.pub >> ~/.ssh/authorized_keys"
echo "     # isi PRIVATE key (~/.ssh/id_ed25519) ke secret GitHub: VPS_SSH_KEY"
echo ""
echo "  5. Siapkan Nginx + Supervisor (lihat deploy/nginx.conf & deploy/hris-queue.conf)"
echo "  6. SSL:  sudo certbot --nginx -d domain-anda.com -d www.domain-anda.com"
echo ""
