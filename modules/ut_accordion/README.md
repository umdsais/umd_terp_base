# UMD Terp Paragraphs: Accordions

Paragraphs integration of accordions for UMD projects. This module provides a way to add an accordion to Kitchen Sink Pages.

This module contains markup only (no js or css), those should be provided in the UMD Terp Theme:

- [UMD Terp Theme](https://github.com/umdsais/umd_terp)

## Configuration

Provides the "Accordion" and "Accordion Item" paragraphs.

The following fields are available on the Accordion KS widget:

- Open First: Defines if the first accordion item should be open by default.
- Open Multiple: Defines if multiple accordions can be open at once.

The following fields are available on the Accordion Item KS widget:

- Title: The clickable header for the accordion item.
- Content: Paragraphs field that allows one to add Text, Blockquote, Divider, Button, Table, Webform or View.

## URL Hash Linking

Each accordion item is assigned a unique ID based on its paragraph ID (e.g. `accordion-item-123`). Linking directly to a URL hash will automatically open and scroll to the matching item:

```
https://example.com/page#accordion-item-123
```

This behavior is provided by the `umd_terp_base/element-hash-open` library and also responds to in-page `hashchange` events. See `umd_terp_base/js/element-hash-open.js` for implementation details.

## Markup Overrides

- You may override paragraphs templates by copying them into the client theme.
- You may override hooks by copying into client .theme, and modifying hook name/etc.
