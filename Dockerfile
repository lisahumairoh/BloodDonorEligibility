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

# === FINAL FIX: FORCE REMOVE CONFLICTING MPMs ===
# Railway kadang me-load mpm_event secara default, kita paksa hapus manual symlink-nya
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    && rm -f /etc/apache2/mods-enabled/mpm_event.conf \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.load \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.conf \
    && a2enmod mpm_prefork

# 7. Expose Port
EXPOSE 80
