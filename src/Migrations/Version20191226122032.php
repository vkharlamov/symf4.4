<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191226122032 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('SET FOREIGN_KEY_CHECKS=0');

        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085644B89032C');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564A76ED395');
        $this->addSql('DROP INDEX IDX_5A1085644B89032C ON vote');
        $this->addSql('DROP INDEX IDX_5A108564A76ED395 ON vote');

        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085644B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A1085644B89032C ON vote (post_id)');
        $this->addSql('CREATE INDEX IDX_5A108564A76ED395 ON vote (user_id)');

        $this->addSql('ALTER TABLE vote CHANGE vote vote INT NOT NULL');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A1085644B89032CA76ED395 ON vote (post_id, user_id)');

//        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX IDX_5A1085644B89032C ON vote (post_id)');
        $this->addSql('CREATE INDEX IDX_5A108564A76ED395 ON vote (user_id)');

        $this->addSql('DROP INDEX UNIQ_5A1085644B89032CA76ED395 ON vote');
        $this->addSql('ALTER TABLE vote CHANGE vote vote INT DEFAULT NULL');
    }
}
