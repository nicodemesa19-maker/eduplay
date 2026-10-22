# EduPlay - Render Deployment Guide

This guide will help you deploy your EduPlay Laravel application to Render.

## Prerequisites

- GitHub repository: https://github.com/nicodemesa19-maker/eduplay
- Render account (free): https://render.com/

## Deployment Steps

### Option 1: Using Render Dashboard (Recommended)

#### Step 1: Create a New Web Service

1. Go to https://render.com and sign in
2. Click **"New +"** button in the top right
3. Select **"Web Service"**

#### Step 2: Connect Your GitHub Repository

1. Click **"Connect account"** if you haven't connected GitHub yet
2. Select your repository: `nicodemesa19-maker/eduplay`
3. Click **"Connect"**

#### Step 3: Configure the Service

Fill in the following settings:

**Basic Settings:**
- **Name:** `eduplay` (or your preferred name)
- **Region:** Choose the closest to your users
- **Branch:** `main`
- **Root Directory:** (leave empty)
- **Runtime:** `PHP`
- **Build Command:** `bash render-build.sh`
- **Start Command:** `php artisan serve --host=0.0.0.0 --port=$PORT`

**Instance Type:**
- Select **Free** (for testing) or **Starter** (for production)

#### Step 4: Add Environment Variables

Click **"Advanced"** and add these environment variables:

```
APP_NAME=EduPlay
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://your-app-name.onrender.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@eduplay.com
MAIL_FROM_NAME=EduPlay
```

**Important Notes:**
- **APP_KEY:** Generate this by running `php artisan key:generate --show` locally and copy the output
- **APP_URL:** Replace with your actual Render URL (you'll get this after deployment)

#### Step 5: Optional - Add PostgreSQL Database

If you prefer PostgreSQL instead of SQLite:

1. Create a new PostgreSQL database in Render:
   - Click **"New +"** → **"PostgreSQL"**
   - Name it `eduplay-db`
   - Select the Free plan

2. Update your environment variables:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=<from Render PostgreSQL dashboard>
   DB_PORT=5432
   DB_DATABASE=<from Render PostgreSQL dashboard>
   DB_USERNAME=<from Render PostgreSQL dashboard>
   DB_PASSWORD=<from Render PostgreSQL dashboard>
   ```

#### Step 6: Deploy

1. Click **"Create Web Service"**
2. Render will automatically:
   - Clone your repository
   - Run the build script
   - Install dependencies
   - Run migrations
   - Start your application

3. Wait for the deployment to complete (5-10 minutes)

#### Step 7: Generate Application Key (If needed)

If you didn't set APP_KEY:

1. Go to your service's **Shell** tab in Render dashboard
2. Run: `php artisan key:generate --show`
3. Copy the output
4. Go to **Environment** tab
5. Update `APP_KEY` with the generated value
6. Save and redeploy

### Option 2: Using render.yaml (Infrastructure as Code)

1. The `render.yaml` file is already in your repository
2. Go to Render dashboard
3. Click **"New +"** → **"Blueprint"**
4. Connect your repository
5. Render will automatically detect `render.yaml` and configure everything

## Post-Deployment Steps

### 1. Verify Deployment

Visit your Render URL: `https://your-app-name.onrender.com`

### 2. Create Admin Account

Access the shell from Render dashboard and run:
```bash
php artisan tinker
```

Then create an admin user:
```php
$user = new App\Models\User();
$user->username = 'admin';
$user->email = 'admin@eduplay.com';
$user->password = bcrypt('your-secure-password');
$user->first_name = 'Admin';
$user->last_name = 'User';
$user->role = 'admin';
$user->is_active = true;
$user->save();
```

### 3. Configure Domain (Optional)

1. Go to your service's **Settings** tab
2. Scroll to **Custom Domain**
3. Add your custom domain
4. Update DNS records as instructed

## Important Notes

### Free Tier Limitations

- Free tier services spin down after 15 minutes of inactivity
- First request after spin-down may take 30-60 seconds
- Free tier suitable for testing/development only

### Persistence

- SQLite database files persist on Render's disk
- For production, consider using PostgreSQL database
- Files in `storage/app` and `storage/logs` persist

### Troubleshooting

#### Build Fails

1. Check build logs in Render dashboard
2. Common issues:
   - Missing Node.js: Render includes Node.js by default
   - PHP version: Ensure PHP 8.2+ (specify in `composer.json`)
   - Memory issues: Upgrade to paid plan

#### Application Errors

1. Check logs: Service → Logs tab
2. Enable debug mode temporarily:
   - Set `APP_DEBUG=true` in environment variables
   - Redeploy
   - Check error messages
   - Set back to `false` for production

#### Database Issues

1. Run migrations manually:
   - Go to Shell tab
   - Run: `php artisan migrate --force`

2. Check database connection:
   - For SQLite: `php artisan tinker` → `DB::connection()->getPdo()`
   - For PostgreSQL: Verify credentials

#### Static Assets Not Loading

1. Ensure build script runs `npm run build`
2. Check if assets are in `public/build` directory
3. Verify `APP_URL` matches your Render URL

### Performance Optimization

1. **Enable OPcache:** Render enables this by default
2. **Use Queue Workers:** For email/background jobs
3. **CDN for Assets:** Consider Cloudflare for static files
4. **Upgrade Plan:** Starter plan provides better performance

## Updating Your Application

After making changes locally:

```bash
git add .
git commit -m "Your update description"
git push origin main
```

Render will automatically detect the push and redeploy.

## Environment Variables Quick Reference

| Variable | Value | Description |
|----------|-------|-------------|
| APP_NAME | EduPlay | Application name |
| APP_ENV | production | Environment |
| APP_DEBUG | false | Debug mode (false for production) |
| APP_KEY | base64:... | Application encryption key |
| APP_URL | https://... | Your Render URL |
| DB_CONNECTION | sqlite or pgsql | Database type |
| SESSION_DRIVER | database | Session storage |
| CACHE_STORE | database | Cache storage |

## Support

- Render Docs: https://render.com/docs
- Laravel Docs: https://laravel.com/docs
- GitHub Issues: https://github.com/nicodemesa19-maker/eduplay/issues

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secured
- [ ] HTTPS enabled (automatic on Render)
- [ ] `.env` file not in repository
- [ ] Regular security updates

---

**Ready to deploy?** Follow the steps above and your EduPlay application will be live in minutes! 🚀
