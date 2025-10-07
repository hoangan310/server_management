# ImageService Documentation

The `ImageService` is a reusable service for handling image uploads, resizing, and management in the Laravel application.

## Features

-   Image upload with validation
-   File type validation (JPEG, PNG, GIF, WebP)
-   File size validation (5MB limit)
-   Automatic filename generation
-   Image resizing (requires intervention/image package)
-   Thumbnail generation
-   File deletion
-   URL generation

## Usage

### Basic Upload

```php
use App\Services\ImageService;

$imageService = app(ImageService::class);

// Upload without resizing
$path = $imageService->upload($uploadedFile, 'submissions');

// Upload with resizing (requires intervention/image)
$path = $imageService->upload($uploadedFile, 'submissions', 300, 300);
```

### Delete Image

```php
$imageService->delete($path);
```

### Get Image URL

```php
$url = $imageService->url($path);
```

### Check if File Exists

```php
$exists = $imageService->exists($path);
```

### Get File Size

```php
$size = $imageService->size($path);
```

### Create Thumbnail

```php
$thumbnailPath = $imageService->createThumbnail($originalPath, 150, 150);
```

## Configuration

The service uses the following default configuration:

-   **Disk**: `public`
-   **Directory**: `images`
-   **Allowed MIME types**: `image/jpeg`, `image/png`, `image/gif`, `image/webp`
-   **Max file size**: 5MB

You can customize these by passing parameters to the constructor:

```php
$imageService = new ImageService('local', 'uploads');
```

## Installation Requirements

### For Basic Functionality

No additional packages required.

### For Image Resizing and Thumbnails

Install the Intervention Image package:

```bash
composer require intervention/image
```

After installation, uncomment the Intervention Image code in the `ImageService` class.

## File Structure

Images are stored in the following structure:

```
storage/app/public/images/
├── submissions/
│   ├── filename1.jpg
│   ├── filename2.png
│   └── thumbnails/
│       ├── filename1_thumb.jpg
│       └── filename2_thumb.png
└── other-category/
    └── ...
```

## Security Features

-   File type validation
-   File size limits
-   Secure filename generation
-   Path traversal protection
-   MIME type verification

## Error Handling

The service throws `InvalidArgumentException` for:

-   Invalid file types
-   Files exceeding size limits
-   Invalid parameters

Always wrap service calls in try-catch blocks:

```php
try {
    $path = $imageService->upload($file, 'submissions');
} catch (InvalidArgumentException $e) {
    // Handle validation errors
}
```
