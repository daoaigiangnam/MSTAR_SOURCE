# VideoDubing-AI-Frontend

Frontend riêng cho hệ thống VideoDubbing AI.

## Architecture

- PHP thuần
- HTML / CSS / JavaScript
- Responsive desktop + mobile
- Frontend gọi `VideoDubing-AI` API, không xử lý media core trực tiếp
- Workflow: Video → Subtitle → Align → Dubbing → Preview → Result
- Jobs/progress được theo dõi qua API

## Structure

```text
config/          API configuration and client
includes/        Shared PHP layout/components
pages/video/     URL, metadata, download and files
pages/subtitle/  Extract, edit, align, export
pages/dubbing/   Voice, generate, preview
pages/processing Jobs and progress
pages/settings/  Frontend settings
assets/css/      Modular styles
assets/js/       Modular JavaScript
uploads/         Runtime uploads
storage/         Runtime storage
downloads/       Runtime downloads
```

## Development

Set the API endpoint with `VIDEO_DUBBING_API_URL`.

Example:

```text
VIDEO_DUBBING_API_URL=http://localhost:3000
```

The initial frontend skeleton is now in place. Functional API integration will be implemented module-by-module and tested before moving to the next module.
