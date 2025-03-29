# Laravel Makale Yayınlama Projesi

Bu proje, Laravel framework'ü kullanarak geliştirilen ve makale yayınlama platformu sunan bir uygulamadır. Kullanıcılar, makale ekleyebilir, makalelere yorum yapabilir ve yorumlara çocuk yorumlar ekleyebilir. Ayrıca admin paneli üzerinden makale, kategori, kullanıcı, yorumlar ve site ayarları yönetilebilir. Proje, tüm işlemleri loglayarak yönetimsel verilerin takibini kolaylaştırmaktadır.

## Özellikler

### 1. **Ana Sayfa**
Ana sayfada aşağıdaki bölümler bulunmaktadır:
- **En Çok Okunan Makaleler:** Kullanıcıların ilgisini çeken ve popüler hale gelen makaleler.
- **Son Yüklenen Makaleler:** Yeni eklenen makaleler.
- **Kategoriler:** Kullanıcıların makale içeriklerini kolayca filtreleyebileceği kategori listesi.

#### Örnek Ana Sayfa:

![Ana Sayfa Görseli](images/anaSayfa1.png)
![Ana Sayfa Görseli](images/anaSayfa2.png)

### 2. **Kategori ve Makale Detayı**
Her kategoriye ait detaylı sayfa bulunmaktadır. Kategoriler altında listelenen makalelere tıklayarak, her bir makale hakkında daha fazla bilgi edinebilir, yorum yapabilir ve çocuk yorumlar bırakabilirsiniz.

#### Kategori Sayfası:
![Kategori Sayfası](images/kategori1.png)

#### Makalelerin Hepsinin Bulunduğu Sayfa:
![Makaleler Görsel](images/makale1.png)

#### Makale Detay Sayfası:
![Makaleye Tıklanmış Görsel](images/makale2.png)
![Makaleye Tıklanmış Görsel](images/makale3.png)

#### Örnek Hata Mesajı:
![Makalede Olan Bir Oturum Hatası Görsel](images/makaleHataIcerik.png)


### 3. **Yorum ve Çocuk Yorumlar**
Kullanıcılar, makalelere yorum bırakabilir ve her bir yoruma çocuk yorumlar ekleyebilir. Bu özellik, daha detaylı etkileşimler ve tartışmaların yapılmasına olanak tanır.

#### Yorumlar:
![Yorumlar Görseli](images/makaleCocukYorum.png)

### 4. **Kullanıcı Kayıt ve E-posta Doğrulama**
Kullanıcılar, siteye üye olduklarında, e-posta adreslerine doğrulama maili gönderilir. E-posta doğrulama işlemi başarıyla tamamlandığında, kullanıcı aktif hale gelir. Ayrıca, "Şifremi Unuttum" özelliğiyle kullanıcılar şifrelerini sıfırlayabilirler.

#### E-posta Doğrulama: Admin Tarafından Custom Logolu Tema eklenebiliyor.
![E-posta Doğrulama Görseli](images/verifyEmail.png)

#### Parolamı Unuttum:
![Parolamı Unuttum Görseli](images/passwordReset.png)

### 5. **Admin Paneli**
Admin paneli, site yöneticilerinin kategorileri, makaleleri, kullanıcıları ve yorumları yönetmesine olanak tanır. Adminler ayrıca her işlem için durum değiştirme (aktif/pasif) işlemlerini gerçekleştirebilir ve tüm işlemlerin loglarını görüntüleyebilir.

#### Admin Paneli:
![Admin Paneli Görseli](images/adminMain.png)

#### Admin Paneli - Kategori Yönetimi:
![Kategori Yönetimi](images/adminKategoriCreate.png)
![Kategori Yönetimi](images/adminKategoryList.png)

#### Admin Paneli - Makale Yönetimi:
![Makaleler Yönetimi](images/adminMakaleCreate.png)
![Makaleler Yönetimi](images/adminMakaleList.png)

#### Admin Paneli - Kullanıcı Yönetimi:
![Kullanıcılar Yönetimi](images/adminUserCreate.png)
![Kullanıcılar Yönetimi](images/adminUserList.png)

### 6. **E-posta ve Tema Yönetimi**
Admin panelinde, e-posta teması seçimi ve yeni tema oluşturma gibi özellikler bulunmaktadır. Bu sayede site içindeki tüm e-posta bildirimleri ve görsel temalar özelleştirilebilir.

#### Tema Seçimi:
![Tema Seçimi](images/adminTemaCreate1.png)
![Tema Seçimi](images/adminTemaCreate2.png)
![Tema Seçimi](images/adminTemaList.png)

### 7. **İşlem Günlükleri (Logs)**
Proje, tüm yönetimsel işlemleri kaydeder. Adminler yapılan işlemleri günlüklere bakarak takip edebilirler. Bu loglar, güvenlik ve izleme açısından önemlidir.

#### Loglar:
![Loglar Görseli](images/adminLog.png)

### 8. **Listeleme ve Filtreleme**
Projede, tüm listeleme sayfalarında filtreleme yapılabilir. Kategoriler veya makaleler, kullanıcıların belirlediği kriterlere göre listelenebilir. Bu özellik, kullanıcıların aradıkları içeriği kolayca bulmalarını sağlar.

#### Filtreleme Özelliği:
![Filtreleme Görseli](images/adminFiltre.png)

### 9. **Site İçin Basit Ayar Ekranı**
Admin panelinde, site içi temel ayarların yapılabildiği bir ekran yer almaktadır. Bu ekran, siteyi özelleştirmek ve yönetmek için gerekli temel ayarları yapmanızı sağlar.

#### Site Ayarları:
![Site Ayarları](images/adminSettings1.png)
![Site Ayarları](images/adminSettings2.png)
![Site Ayarları](images/adminSettings3.png)


### Gereksinimler
- PHP 8.0 veya üstü
- Composer
- Laravel 8.x veya üstü
- MySQL veya benzeri bir veritabanı

# MakaleYayinlamaLaravel
# MakaleYayinlamaLaravel
# MakaleYayinlamaLaravel
# MakaleYayinlamaLaravel
# MakaleYayinlamaLaravel
# MakaleYayinlamaLaravel
# makaleYayinlamaLaravel
