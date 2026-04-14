# Deployment Guide: Render + Aiven MySQL + Docker

This guide walks you through deploying your Laravel Payroll System to Render using Docker and Aiven for MySQL.

## Prerequisites
1. A [Render](https://render.com) account.
2. An [Aiven](https://aiven.io) account with a MySQL service running.
3. Your code is pushed to a GitHub or GitLab repository.

---

## Step 1: Prepare Aiven MySQL
1. Log in to your Aiven Console.
2. Select your MySQL service.
3. Under **Connection Information**, copy the following:
   - **Host**
   - **Port** (usually 3306)
   - **User** (usually `avnadmin`)
   - **Password**
   - **Database Name** (usually `defaultdb`)
4. Ensure you have the `ca.pem` file in your project root (which you've already confirmed).

---

## Step 2: Deploy to Render
1. Log in to [Render](https://dashboard.render.com).
2. Click **New +** and select **Blueprint**.
3. Connect your GitHub/GitLab repository.
4. Render will automatically detect the `render.yaml` file.
5. You will be prompted to fill in the following environment variables:
   - `DB_HOST`: Your Aiven MySQL Host.
   - `DB_DATABASE`: Your Aiven MySQL Database name.
   - `DB_USERNAME`: Your Aiven MySQL Username.
   - `DB_PASSWORD`: Your Aiven MySQL Password.
6. Click **Apply**.

---

## Step 3: Verify Deployment
1. Wait for Render to build the Docker image and deploy the service.
2. Once the status is **Live**, click the URL provided by Render.
3. Your application should be running!

---

## Configuration Details

### Database Connections
The application is configured to use SSL for MySQL since Aiven requires it. 
- In `config/database.php`, we use `MYSQL_ATTR_SSL_CA`.
- In `render.yaml`, we set `MYSQL_ATTR_SSL_CA` to `/app/ca.pem`.

### Persistent Storage
> [!IMPORTANT]
> This setup uses ephemeral storage. If you upload files (e.g., employee photos), they will be lost when the service restarts.
> To persist files, you should add a **Render Disk** or use **AWS S3** for file storage.

### Migrations
The `docker/run.sh` script automatically runs `php artisan migrate --force` on every deployment. You don't need to run it manually.

---

## Troubleshooting
- **Database Connection Failed**: Double-check your Aiven credentials and ensure Aiven's firewall allows connections from "0.0.0.0/0" (or Render's IP range if you have a static IP set up).
- **Build Errors**: Check the Render logs for details. Common issues include missing PHP extensions or Vite build failures.
