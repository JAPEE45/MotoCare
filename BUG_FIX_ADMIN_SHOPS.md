# Admin Shop Management - Bug Fixes

## Issue Identified
**Error:** `TypeError: Cannot read properties of undefined (reading 'forEach')`
**Location:** `admin-shop-management.js:34` in `renderShops()` function
**Root Cause:** Response structure mismatch between backend PHP and frontend JavaScript

## Fixes Applied

### 1. Backend Response Structure (`helper/adminGetShops.php`)
**Changed:**
- Response key from `'shops'` to `'data'`
- Field name from `'id'` to `'shop_id'` (to match JavaScript expectations)
- Field name from `'lng'` to `'lg'` (to match database column name)
- Removed `'status'` field (not in database)

**Added:**
- Database connection validation
- Query error handling
- Better error messages

```php
// BEFORE
echo json_encode([
    'status' => 'success',
    'shops' => $shops  // ❌ Wrong key
]);

// AFTER
echo json_encode([
    'status' => 'success',
    'data' => $shops  // ✅ Correct key
]);
```

### 2. Frontend Error Handling (`assets/scripts/admin-shop-management.js`)

**Enhanced `loadShops()` function:**
- Added response text logging for debugging
- Added JSON parse error handling
- Validate `data.data` exists before assignment
- Better error messages with actual error details

**Enhanced `renderShops()` function:**
- Check if `tbody` element exists
- Validate `shops` array exists and has items
- Show "No shops found" message for empty arrays

**Added console logging:**
```javascript
console.log('Fetching shops...');
console.log('Response status:', response.status);
console.log('Raw response:', text);
console.log('Parsed data:', data);
```

### 3. HTML Cleanup (`html/admin/shop-management.php`)
**Removed:**
- Leftover navbar HTML fragments
- Duplicate closing tags
- Unused profile dropdown code

**Fixed:**
- Proper content wrapper structure
- Correct div nesting

## Testing Instructions

### Method 1: Direct API Test
1. Open browser: `http://localhost/MotoCare/html/admin/test-api.html`
2. Click "Test API" button
3. You should see JSON response with shop data

**Expected Response:**
```json
{
  "status": "success",
  "data": [
    {
      "shop_id": 1,
      "shop_name": "Molje Lube",
      "owner_name": "Owner Name",
      "contact": "123-456-7890",
      "email": "owner@example.com",
      "address": "San Roque St, Virac, Catanduanes",
      "lat": 13.5875,
      "lg": 124.237,
      "total_bookings": 5,
      "completed_bookings": 3,
      "total_revenue": 1500
    }
  ]
}
```

### Method 2: Full Shop Management Test
1. Create admin account (if not done):
   - Visit: `http://localhost/MotoCare/helper/createAdminAccount.php`
   
2. Login as admin:
   - URL: `http://localhost/MotoCare/html/signin.php`
   - Email: `admin@motocare.com`
   - Password: `admin123`

3. Navigate to Shop Management:
   - Click "Shop Management" in sidebar
   - OR visit: `http://localhost/MotoCare/html/admin/shop-management.php`

4. Open browser console (F12):
   - Look for console logs showing fetch progress
   - Check for any errors in red

**Expected Console Output:**
```
Fetching shops...
Response status: 200
Raw response: {"status":"success","data":[...]}
Parsed data: {status: "success", data: Array(3)}
Shops loaded: 3
```

### Method 3: Direct Backend Test
Open: `http://localhost/MotoCare/helper/adminGetShops.php`

You should see JSON response (not HTML error page)

## Common Issues & Solutions

### Issue: "No shops found" message
**Cause:** Database is empty
**Solution:** Add shops manually in database or use "Add New Shop" button

### Issue: JSON parse error
**Cause:** PHP error before JSON output
**Solution:** 
1. Check XAMPP Apache and MySQL are running
2. Verify database `motocare` exists
3. Check `helper/db.php` connection settings
4. Look for PHP errors in Apache error logs

### Issue: 404 Not Found
**Cause:** Wrong file path
**Solution:** 
- Ensure you're accessing from `html/admin/shop-management.php`
- Check file exists: `helper/adminGetShops.php`

### Issue: Empty response
**Cause:** Database connection failed
**Solution:**
1. Check MySQL is running in XAMPP
2. Verify database name is `motocare`
3. Check credentials in `helper/db.php`

### Issue: "Cannot read properties of undefined"
**Cause:** Response structure mismatch
**Solution:** Already fixed! Clear browser cache and refresh

## Database Verification

Run this SQL to check shops exist:
```sql
SELECT * FROM shop;
```

Expected columns:
- `id` (int)
- `name` (varchar)
- `address` (varchar)
- `lat` (varchar)
- `lg` (varchar) ⚠️ Note: 'lg' not 'lng'
- `owner_id` (int)
- `icon` (varchar)
- `rating` (int)
- `hours` (varchar)

## Files Modified

1. ✅ `helper/adminGetShops.php` - Fixed response structure
2. ✅ `assets/scripts/admin-shop-management.js` - Enhanced error handling
3. ✅ `html/admin/shop-management.php` - Cleaned up HTML
4. ✅ `html/admin/test-api.html` - Created for testing

## What to Check After Refresh

1. **Browser Console (F12):**
   - No red errors
   - See "Shops loaded: X" message
   - See parsed data object

2. **Shop Table:**
   - Shows existing shops from database
   - Has 7 columns (Shop, Owner, Contact, Address, Bookings, Revenue, Actions)
   - Action buttons work (View, Edit, Delete)

3. **Search:**
   - Type shop name to filter
   - Results update in real-time

4. **Add Shop:**
   - Click "Add New Shop"
   - Modal opens with form
   - Map displays for location selection

## Next Steps After Testing

1. If shops load successfully:
   - Test "Add New Shop" functionality
   - Test "Edit Shop" functionality
   - Test "Delete Shop" functionality
   - Test search/filter

2. If errors persist:
   - Share console output (F12)
   - Share network tab response (F12 → Network)
   - Check `test-api.html` results

## Debugging Commands

**Check database connection:**
```php
<?php
include 'helper/db.php';
echo $conn ? "Connected!" : "Failed!";
?>
```

**Check shop count:**
```php
<?php
include 'helper/db.php';
$result = $conn->query("SELECT COUNT(*) as count FROM shop");
$row = $result->fetch_assoc();
echo "Shops in database: " . $row['count'];
?>
```

## Prevention

To avoid similar issues in future:
1. Always validate API response structure
2. Check if data exists before using `.forEach()`
3. Use `console.log()` to debug API responses
4. Handle errors in both frontend and backend
5. Test API endpoints directly before integrating

## Summary

The issue was a **response structure mismatch**:
- Backend was sending `{status: 'success', shops: [...]}`
- Frontend was expecting `{status: 'success', data: [...]}`

This has been **FIXED** by:
1. Updating backend to send `data` key
2. Adding validation to check `data.data` exists
3. Improving error messages for debugging
4. Cleaning up HTML structure

**Status:** ✅ RESOLVED
