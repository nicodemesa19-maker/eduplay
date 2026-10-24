# Supabase Storage Setup for Avatars

## Step 1: Create Storage Bucket in Supabase Dashboard

1. Go to your Supabase Dashboard: https://supabase.com/dashboard
2. Select your project: `jekrvaznstmyflfdybuf`
3. Click on **Storage** in the left sidebar
4. Click **"New bucket"**
5. Set the following:
   - **Name:** `avatars`
   - **Public bucket:** ✅ Checked (so avatars are publicly accessible)
   - **File size limit:** 2 MB
   - **Allowed MIME types:** `image/jpeg, image/jpg, image/png, image/gif`
6. Click **"Create bucket"**

## Step 2: Get Your Supabase Storage URL and Service Key

### Get Storage URL:
Your Supabase URL is: `https://jekrvaznstmyflfdybuf.supabase.co`

### Get Service Key (anon/public key):
1. In Supabase Dashboard, go to **Settings** → **API**
2. Copy the **`anon` / `public`** key (this is safe to use in your app)
3. Or if you need full access, copy the **`service_role`** key (keep this secret!)

## Step 3: Update Your .env File

Add these lines to your `.env` file:

```env
# Supabase Storage Configuration
SUPABASE_STORAGE_URL=https://jekrvaznstmyflfdybuf.supabase.co
SUPABASE_SERVICE_KEY=your-service-role-key-here
SUPABASE_STORAGE_BUCKET=avatars
```

## Step 4: Set Bucket Policies (Important!)

In Supabase Dashboard → Storage → avatars bucket → **Policies**:

### Policy 1: Allow Public Read Access
```sql
CREATE POLICY "Public Access"
ON storage.objects FOR SELECT
USING ( bucket_id = 'avatars' );
```

### Policy 2: Allow Authenticated Users to Upload
```sql
CREATE POLICY "Authenticated users can upload avatars"
ON storage.objects FOR INSERT
TO authenticated
WITH CHECK ( bucket_id = 'avatars' );
```

### Policy 3: Allow Users to Update Their Own Avatars
```sql
CREATE POLICY "Users can update own avatars"
ON storage.objects FOR UPDATE
TO authenticated
USING ( bucket_id = 'avatars' );
```

### Policy 4: Allow Users to Delete Their Own Avatars
```sql
CREATE POLICY "Users can delete own avatars"
ON storage.objects FOR DELETE
TO authenticated
USING ( bucket_id = 'avatars' );
```

## Step 5: Test on Render

1. Commit and push all changes to GitHub
2. Render will auto-deploy
3. Add the environment variables in Render Dashboard:
   - Go to your Render service
   - Click **Environment**
   - Add:
     - `SUPABASE_STORAGE_URL` = `https://jekrvaznstmyflfdybuf.supabase.co`
     - `SUPABASE_SERVICE_KEY` = `your-key-here`
     - `SUPABASE_STORAGE_BUCKET` = `avatars`
4. Save changes (Render will redeploy)
5. Upload a test avatar - it should now persist!

## Verification

After setup, your avatars will be stored at URLs like:
```
https://jekrvaznstmyflfdybuf.supabase.co/storage/v1/object/public/avatars/avatar_21_1729734002.png
```

These URLs will work even after Render restarts! 🎉

## Troubleshooting

**Issue:** Avatar upload fails
- Check that `SUPABASE_SERVICE_KEY` is set correctly in `.env` (local) and Render (production)
- Verify the bucket name is exactly `avatars`
- Check Supabase Dashboard → Storage to see if files are being created

**Issue:** "Access Denied" error
- Make sure bucket is set to **Public**
- Verify the storage policies are created (see Step 4)

**Issue:** Old avatars still showing
- The app will fallback to local storage if Supabase isn't configured
- Check that environment variables are set in Render
