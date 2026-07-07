# Canva API Capabilities & Limitations for Your Project

This document outlines what the **Canva Connect API (REST API)** supports, what it does NOT support, and how it impacts your wedding platform project where hosts create and edit ceremonies.

## 1. What the Canva Connect API CAN Do

The Canva Connect API is powerful for integrating Canva workflows, but it is built strictly around **individual user accounts**.

Supported features include:
- **OAuth Authentication:** Users can log into their own Canva accounts directly from your website.
- **Create Designs from Templates:** If you provide a Canva Template ID (`asset_id`), you can use the API to automatically generate a brand new design inside the user's Canva account.
- **Export Designs:** You can use the API to request a download link for a finished design (e.g., as a JPG or PDF).
- **Upload Assets:** You can programmatically upload images (like a couple's photos) into a user's Canva media library so they can use them in their designs.
- **Folder Management:** You can create folders in a user's Canva account to organize their designs.

## 2. What the Canva Connect API CANNOT Do (Critical Limitations for Your Use Case)

Based on your goal to have hosts fill out a form on your website and automatically generate a finished design without logging into Canva, here are the major limitations:

### A. No "White-Label" Editing (Hidden Accounts)
**Limitation:** Canva does not allow you to embed an invisible editor on your website. 
**Impact:** If a host wants to use the Canva Editor to drag-and-drop elements, they **must** have their own personal Canva account and they **must** authenticate via the login screen. You cannot use your single Admin account to secretly power the editing for hundreds of hosts.

### B. No Free/Standard Autofill (Form-to-Design)
**Limitation:** If you want a host to fill out a web form (e.g., "Bride Name", "Date") and automatically replace text inside a Canva template *without opening the editor*, Canva's standard API **does not support this**.
**Exception (Enterprise Only):** Canva recently introduced an "Autofill API", but it is strictly limited to **Canva Enterprise** accounts. Even then, it is designed for bulk operations by large corporations, not for providing SaaS templates to individual users.

## 3. How to Approach Your Specific Requirements

Your requirement is: **"I want a host to choose a template, fill out a ceremony form (PC/Mobile), and automatically get the design."**

Because of Canva's limitations, you have two distinct paths forward for this project:

### Path 1: The Canva Way (Manual Editing)
If you keep Canva, the flow must be:
1. The host clicks "Design Ceremony".
2. They are redirected to Canva and must log in.
3. Your app generates a new design from your template.
4. The host manually types their names and details inside the Canva editor.
5. They finish and export it back to your site.

### Path 2: The Automated Way (Abandoning Canva)
If you want the host to simply fill a form on your site and instantly receive the finished PC/Mobile design (no Canva login required), you must use a different technology:
1. **Bannerbear or Placid API:** These are dedicated APIs that allow you to design templates and push form data directly into them to generate images.
2. **HTML/CSS to Image:** You build the templates in Laravel using HTML/CSS. The form data is injected via Blade templates, and a tool like `spatie/browsershot` instantly converts that HTML into a final image.

---
*Note: This documentation is based on the official Canva Developers documentation (https://www.canva.dev/docs/connect/)*
