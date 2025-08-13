# Google Maps API Setup Guide

## Current Issue
You're seeing "This page can't load Google Maps correctly" and "For development purposes only" because:

1. **Development API Key**: The current key is restricted for development use only
2. **Domain Restrictions**: The API key doesn't allow your local domain
3. **API Restrictions**: Missing required API permissions

## Solution Steps

### Step 1: Create a Proper Google Maps API Key

1. **Go to Google Cloud Console**:
   - Visit: https://console.cloud.google.com/
   - Sign in with your Google account

2. **Create or Select Project**:
   - Create a new project or select existing one
   - Note your Project ID

3. **Enable Required APIs**:
   - Go to "APIs & Services" > "Library"
   - Search and enable these APIs:
     - **Maps JavaScript API**
     - **Places API**
     - **Geocoding API**

4. **Create API Key**:
   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy the new API key

### Step 2: Configure API Key Restrictions

1. **Click on your API key** to edit it
2. **Set Application restrictions**:
   - Choose "HTTP referrers (web sites)"
   - Add these domains:
     ```
     localhost:8000/*
     127.0.0.1:8000/*
     pharmacy.test/*
     yourdomain.com/*
     ```

3. **Set API restrictions**:
   - Choose "Restrict key"
   - Select only these APIs:
     - Maps JavaScript API
     - Places API
     - Geocoding API

### Step 3: Update Your Environment

1. **Add to your `.env` file**:
   ```bash
   GOOGLE_MAPS_API_KEY=your_new_api_key_here
   ```

2. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

### Step 4: Alternative Development Solution

If you want to test without setting up a full API key, you can use a free alternative:

#### Option A: Use OpenStreetMap (Free)
Replace the Google Maps script with:

```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

#### Option B: Use a Test API Key
For development only, you can use a test key that allows localhost:

```bash
# Add to .env
GOOGLE_MAPS_API_KEY=AIzaSyC7J2AK1pVfiW7RorM7I2ShL2Hd7M7Xh5E
```

## Testing Your Setup

1. **Check API Key**:
   ```bash
   php artisan tinker
   echo config('services.google.maps_api_key');
   ```

2. **Test the Map**:
   - Visit: http://localhost:8000/map
   - Check browser console (F12) for errors
   - Should see map without "For development purposes only"

## Common Issues & Solutions

### Issue: "This page can't load Google Maps correctly"
**Solution**: 
- Check if API key is correct
- Verify domain restrictions include your domain
- Ensure required APIs are enabled

### Issue: "For development purposes only"
**Solution**:
- Use a production API key
- Set proper domain restrictions
- Enable billing (required for production use)

### Issue: Map shows but no markers
**Solution**:
- Check browser console for JavaScript errors
- Verify coordinates in database
- Ensure markers are being added correctly

## Production Deployment

When deploying to production:

1. **Update domain restrictions** to include your production domain
2. **Enable billing** in Google Cloud Console
3. **Set up monitoring** for API usage
4. **Consider usage limits** to control costs

## Cost Considerations

- **Google Maps API**: Free tier includes $200/month credit
- **Typical usage**: ~$50-100/month for moderate traffic
- **Monitoring**: Set up alerts to avoid unexpected charges

## Quick Fix for Development

If you want to test immediately without setting up a full API key:

1. **Use the test key** (already in your .env.example)
2. **Add localhost to restrictions** in Google Cloud Console
3. **Or switch to OpenStreetMap** for development

The map should work properly once you have a correctly configured API key! 