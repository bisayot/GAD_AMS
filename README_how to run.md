1. Open vscode and then the terminal tapos run: 
a. cd C:\xampp\htdocs
b. git clone https://github.com/bisayot/GAD_AMS.git && cd GAD_AMS
c. code .

2. download env files:
ada samet ijay gc jay gdrive

3. copy the files in there respective folders base on the names frontend for frontend backend for backend

4. rename the env files accordingly and make sure that they have a period .env, .env.local (both ijay frontend) and then .env ijay backend

5. open terminal and run:
a. cd frontend and cd backend
b. npm install and composer install
c. npm run dev and php spark serve
d. user accounts passwords: admin=admin123!, staff=staff123!, twg=twgbsu123!, non-twg=123456

6. nu agedit kayun tu makikita met ijat source control(left side, jay kasla adda 3 heads na nga snake), pacreate new branch run terminal nu madiyu source control agswitch:
a. git switch -c [nagan ti branch, jay feature nga minodify yu]
b. git add .
c. git commit -m "Describe your changes here"
d. git push -u origin [nagan ti branch, jay feature nga minodify yu]

7. continue editing tapos ijay source control if adda new edits yu, kabil kayo text message below jay changes and then ijay commit adda dropdown click it tapos select jay commit and push

Backup metadata table (local XAMPP MySQL)
-----------------------------------------
The backup files remain stored in Google Drive. The following optional table stores
backup metadata in the configured `gad_submission_system` database:

```sql
CREATE TABLE IF NOT EXISTS `backups` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `drive_file_id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `size_bytes` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `backup_type` VARCHAR(30) NOT NULL DEFAULT 'backup',
  `created_by` BIGINT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_backups_drive_file_id` (`drive_file_id`),
  KEY `idx_backups_created_by` (`created_by`),
  CONSTRAINT `fk_backups_created_by`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

The `created_by` value connects each row to `users.id`. The backup page uses that
connection to display the creator's email and system role.

Column meanings:

- `id`: local identifier for the metadata row.
- `drive_file_id`: Google Drive file ID; uniquely connects the row to the backup archive.
- `name`: backup filename shown in Google Drive.
- `size_bytes`: archive size in bytes.
- `backup_type`: `backup` for a user-created backup or `before-restore` for an automatic safety copy.
- `created_by`: connected `users.id` of the authenticated creator.
- `created_at`: local timestamp when the metadata row was recorded.

The creator's email and role are read from the related `users` row, so they are
not duplicated in the `backups` table. If a user is deleted, `created_by` becomes
`NULL` and the backup file remains available.

The Backup Management page also has a `Restore local backup` button beside the
other page actions. It accepts a `.zip` backup saved on the computer, creates a
new safety backup in Google Drive, then performs the same strict replacement
used by the Drive restore. The exact confirmation text required is `STRICT
RESTORE`.

During strict restore, both `activity_logs` and `backups` are protected. They are
not dropped, replaced, or included in newly generated database backup dumps.

For CodeIgniter environments, the same table is created by running:

```powershell
cd backend
php spark migrate
```