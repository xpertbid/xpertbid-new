# Social Login Setup Guide

## 1. Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable Google+ API
4. Go to Credentials → Create Credentials → OAuth 2.0 Client ID
5. Set Application type to "Web application"
6. Add authorized redirect URIs:
   - `http://localhost:8000/api/auth/google/callback`
   - `http://localhost:3000` (for development)
7. Copy Client ID and Client Secret

## 2. Facebook OAuth Setup

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Add Facebook Login product
4. Go to Facebook Login → Settings
5. Add Valid OAuth Redirect URIs:
   - `http://localhost:8000/api/auth/facebook/callback`
   - `http://localhost:3000` (for development)
6. Copy App ID and App Secret

## 3. Environment Variables

Add these to your `.env` file:

```env
# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/api/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=your_facebook_app_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret_here
FACEBOOK_REDIRECT_URI=http://localhost:8000/api/auth/facebook/callback

# Frontend URLs
FRONTEND_URL=http://localhost:3000
```

## 4. Frontend Environment Variables

Add these to your frontend `.env.local` file:

```env
# Google OAuth (same as backend)
NEXT_PUBLIC_GOOGLE_CLIENT_ID=your_google_client_id_here

# Facebook OAuth (same as backend)
NEXT_PUBLIC_FACEBOOK_APP_ID=your_facebook_app_id_here
```

## 5. Testing

1. Start both servers:
   ```bash
   # Backend
   cd backend && php artisan serve
   
   # Frontend
   cd frontend && npm run dev
   ```

2. Visit http://localhost:3000
3. Click "Login / Register"
4. Try Google and Facebook login buttons

## 6. API Endpoints

- `POST /api/auth/social-login` - Direct social login
- `GET /api/auth/google/redirect` - Google OAuth redirect
- `GET /api/auth/google/callback` - Google OAuth callback
- `GET /api/auth/facebook/redirect` - Facebook OAuth redirect
- `GET /api/auth/facebook/callback` - Facebook OAuth callback
