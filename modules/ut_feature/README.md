# UMD Terp Paragraphs: Feature

Paragraphs integration of the `umd-element-pathway` web component for UMD projects. This module provides a side-by-side image + text feature panel.

This module contains markup only (no js or css), those should be provided in the UMD Terp Theme:

- [UMD Terp Theme](https://github.com/umdsais/umd_terp)

## Configuration

Provides the "Feature" paragraph.

The following fields are available on the Feature widget:

- **Theme**: Sets the visual style of the feature panel.
  - *White (default)*: Standard display, white background.
  - *Light Gray*: Overlay display, light gray background.
  - *Dark*: Overlay display, black background.
- **Reverse**: Flips the image from the left column to the right column.
- **Title**: The headline text.
- **Text**: Body copy (formatted text).
- **Image**: The featured image (rendered via the `optimized` image style).
- **Link**: Optional CTA link. Rendered as a secondary button on white, or a primary button on light/dark themes.

## Markup Overrides

- You may override paragraph templates by copying them into the client theme.
- You may override hooks by copying into the client `.theme` file and modifying the hook name/etc.
