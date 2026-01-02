# Gunakan base image PHP dengan Apache
FROM php:8.2-apache

# 1. Update & Install dependencies sistem
# Kita butuh python3 dan pip untuk menjalankan script ML
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-venv \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*


# 2. Install Library Python (Pandas, Scikit-learn)
# Menggunakan --break-system-packages karena di container environment ini aman
RUN pip3 install --break-system-packages pandas scikit-learn joblib numpy

# 3. Konfigurasi Apache Document Root
# Default apache di docker rootnya di /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Enable modul rewrite apache (jika nanti butuh .htaccess)
RUN a2enmod rewrite

# 5. Copy Source Code Project
WORKDIR /var/www/html
COPY . /var/www/html

# 6. Set Permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# === FINAL ULTIMATE FIX: RUNTIME CONFIGURATION ===
# Kita buat script startup langsung di dalam image agar tidak kena masalah Windows CRLF
# Script ini akan dijalankan SETIAP KALI container start
RUN echo '#!/bin/bash' > /start.sh \
    && echo 'echo "--> Fixing Apache MPM Configuration..."' >> /start.sh \
    && echo 'rm -f /etc/apache2/mods-enabled/mpm_event.load' >> /start.sh \
    && echo 'rm -f /etc/apache2/mods-enabled/mpm_event.conf' >> /start.sh \
    && echo 'rm -f /etc/apache2/mods-enabled/mpm_worker.load' >> /start.sh \
    && echo 'rm -f /etc/apache2/mods-enabled/mpm_worker.conf' >> /start.sh \
    && echo 'a2dismod mpm_event || true' >> /start.sh \
    && echo 'a2dismod mpm_worker || true' >> /start.sh \
    && echo 'a2enmod mpm_prefork' >> /start.sh \
    && echo 'echo "--> Starting Apache..."' >> /start.sh \
    && echo 'exec apache2-foreground' >> /start.sh \
    && chmod +x /start.sh

# 7. Expose Port
EXPOSE 80

# 8. Gunakan script kita sebagai command utama
CMD ["/start.sh"]
