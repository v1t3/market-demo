<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106174508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) DEFAULT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, preview LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', preview_image VARCHAR(255) DEFAULT NULL, detail_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `cart` (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `cart_product` (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_2890CCAA1AD5CDBF (cart_id), INDEX IDX_2890CCAA4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `category` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) DEFAULT NULL, slug VARCHAR(120) NOT NULL, is_deleted TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, total_price DOUBLE PRECISION DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, INDEX IDX_F52993987E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order_product` (id INT AUTO_INCREMENT NOT NULL, app_order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price_per_one NUMERIC(15, 2) NOT NULL, INDEX IDX_2530ADE6851F0D95 (app_order_id), INDEX IDX_2530ADE64584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `product` (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, price NUMERIC(15, 2) NOT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, is_published TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, slug VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_D34A04AD989D9B62 (slug), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `product_image` (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, filename_big VARCHAR(255) NOT NULL, filename_middle VARCHAR(255) NOT NULL, filename_small VARCHAR(255) NOT NULL, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `reset_password_request` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_settings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, logo_filename VARCHAR(255) DEFAULT NULL, domain VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, full_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(30) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, zip_code INT DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `cart_product` ADD CONSTRAINT FK_2890CCAA1AD5CDBF FOREIGN KEY (cart_id) REFERENCES `cart` (id)');
        $this->addSql('ALTER TABLE `cart_product` ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES `product` (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993987E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `order_product` ADD CONSTRAINT FK_2530ADE6851F0D95 FOREIGN KEY (app_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order_product` ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES `product` (id)');
        $this->addSql('ALTER TABLE `product` ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES `category` (id)');
        $this->addSql('ALTER TABLE `product_image` ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES `product` (id)');
        $this->addSql('ALTER TABLE `reset_password_request` ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `cart_product` DROP FOREIGN KEY FK_2890CCAA1AD5CDBF');
        $this->addSql('ALTER TABLE `cart_product` DROP FOREIGN KEY FK_2890CCAA4584665A');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993987E3C61F9');
        $this->addSql('ALTER TABLE `order_product` DROP FOREIGN KEY FK_2530ADE6851F0D95');
        $this->addSql('ALTER TABLE `order_product` DROP FOREIGN KEY FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE `product` DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE `product_image` DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('ALTER TABLE `reset_password_request` DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE `cart`');
        $this->addSql('DROP TABLE `cart_product`');
        $this->addSql('DROP TABLE `category`');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE `order_product`');
        $this->addSql('DROP TABLE `product`');
        $this->addSql('DROP TABLE `product_image`');
        $this->addSql('DROP TABLE `reset_password_request`');
        $this->addSql('DROP TABLE site_settings');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
