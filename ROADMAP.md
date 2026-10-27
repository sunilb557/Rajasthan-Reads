# RajasthanReads Production Roadmap

## Phase 1: Foundation (Priority: Critical)

### 1.1 Real TailwindCSS + Vite Build System
**Status:** Not Started | **Priority:** 🔴 Critical

**Why:** Current CSS is custom - real Tailwind gives:
- Smaller bundle size (purged CSS)
- Dark mode support
- Utility-first development
- Better responsive utilities

**Tasks:**
- [ ] Initialize npm project
- [ ] Install TailwindCSS + Vite
- [ ] Configure `tailwind.config.js` with Rajasthan colors
- [ ] Migrate existing styles to Tailwind classes
- [ ] Set up build pipeline
- [ ] Add dark mode toggle

**Files to create/modify:**
- `package.json`
- `vite.config.js`
- `tailwind.config.js`
- `resources/css/app.css`
- `resources/js/app.js`

### 1.2 Cover Image Upload + Thumbnails
**Status:** Not Started | **Priority:** 🔴 Critical

**Why:** Books need visual appeal for sharing and discovery

**Tasks:**
- [ ] Add `cover_image` column to books table
- [ ] Create image upload handler with validation
- [ ] Add thumbnail generation (GD/Imagick)
- [ ] Support WebP conversion
- [ ] Create responsive image sizes (sm, md, lg)
- [ ] Update admin forms
- [ ] Update book display views

**Storage structure:**
```
public/assets/uploads/
├── books/
│   ├── covers/
│   │   ├── original/
│   │   ├── large/
│   │   ├── medium/
│   │   └── thumbnail/
│   └── pdfs/
```

### 1.3 Auto Slug Generator
**Status:** Not Started | **Priority:** 🔴 Critical

**Why:** Manual slugs are error-prone

**Tasks:**
- [ ] Create Slug helper class
- [ ] Auto-transliterate Hindi/Rajasthani to Latin
- [ ] Handle duplicates with `-2`, `-3` suffix
- [ ] Add uniqueness validation
- [ ] Update admin forms with auto-generation

**Example:**
```
"राजस्थान की लोक कथाएं" → "rajasthan-ki-lok-kathayein"
"Folk Tales of Rajasthan" → "folk-tales-of-rajasthan"
```

### 1.4 Search Engine (FULLTEXT)
**Status:** Not Started | **Priority:** 🔴 Critical

**Why:** Essential for large libraries

**Tasks:**
- [ ] Add FULLTEXT index to books table
- [ ] Create Search controller
- [ ] Build search interface
- [ ] Add autocomplete endpoint
- [ ] Add filters (category, author, district, language)
- [ ] Add search suggestions
- [ ] Track popular searches

**Search features:**
- Instant search (AJAX)
- Faceted filters
- Highlight matching text
- Search within results

## Phase 2: User Experience (Priority: High)

### 2.1 Proper Admin Dashboard
**Status:** Basic version exists | **Priority:** 🟠 High

**Tasks:**
- [ ] Create sidebar navigation layout
- [ ] Add dashboard statistics cards
- [ ] Integrate Chart.js for analytics
- [ ] Add recent activity log
- [ ] Add quick action buttons
- [ ] Mobile-responsive admin

**Dashboard widgets:**
- Total books, authors, categories
- Recent uploads
- Popular books (view count)
- Reading statistics
- Storage usage

### 2.2 Enhanced PDF Reader
**Status:** Basic version exists | **Priority:** 🟠 High

**Tasks:**
- [ ] Add fullscreen mode
- [ ] Add page thumbnails sidebar
- [ ] Add continuous scroll option
- [ ] Mobile swipe gestures
- [ ] Reading themes (light, sepia, dark)
- [ ] Text extraction/copy
- [ ] Improve bookmark UI
- [ ] Add reading progress bar

### 2.3 Book Preview Pages
**Status:** Not Started | **Priority:** 🟠 High

**Why:** SEO booster - Google loves rich content

**Tasks:**
- [ ] Add book excerpt/summary field
- [ ] Add "related books" section
- [ ] Add cultural context section
- [ ] Add download counter display
- [ ] Add rating system
- [ ] Add social sharing buttons

## Phase 3: Advanced Features (Priority: Medium)

### 3.1 Multi-Language Support
**Status:** Not Started | **Priority:** 🟡 Medium

**DB Schema:**
```sql
title_en, title_hi, title_rj
description_en, description_hi, description_rj
```

**Tasks:**
- [ ] Add language columns to books, categories, authors
- [ ] Create language switcher
- [ ] Add translation interface in admin
- [ ] Set language preference in session
- [ ] URL structure: `/hi/books`, `/en/books`

### 3.2 OCR + Full-Text Search
**Status:** Not Started | **Priority:** 🟡 Medium

**Why:** Search INSIDE books - extremely powerful

**Tasks:**
- [ ] Integrate Tesseract OCR
- [ ] Extract text from uploaded PDFs
- [ ] Create `book_pages` table
- [ ] Index page content
- [ ] Add "search in book" feature
- [ ] Show search results with context

**Alternative:** Use Elasticsearch/Meilisearch later

### 3.3 Queue System for Uploads
**Status:** Not Started | **Priority:** 🟡 Medium

**Why:** Large PDFs need background processing

**Tasks:**
- [ ] Set up queue table
- [ ] Create Queue worker
- [ ] Background thumbnail generation
- [ ] Background OCR extraction
- [ ] Progress tracking for uploads

### 3.4 Advanced SEO Pages
**Status:** Not Started | **Priority:** 🟡 Medium

**Create pages:**
- `/district/{slug}` - District-specific books
- `/festival/{slug}` - Festival archives
- `/folk-story/{slug}` - Individual folk stories
- `/culture/{topic}` - Cultural topics

**Content strategy:**
- "Best Folk Stories of Rajasthan"
- "History of Marwar"
- "Rajasthani Literature PDF"
- "Vijaydan Detha Books"

## Phase 4: Production Ready (Priority: Medium)

### 4.1 Security Hardening
**Status:** Basic CSRF exists | **Priority:** 🟡 Medium

**Tasks:**
- [ ] Add rate limiting (login, API)
- [ ] Add CSP headers
- [ ] Strict MIME type validation
- [ ] Image sanitization (remove EXIF, validate)
- [ ] Brute-force protection
- [ ] SQL injection audit (already using PDO prepared)
- [ ] XSS audit (already escaping output)

### 4.2 Performance Optimization
**Status:** Not Started | **Priority:** 🟢 Low

**Tasks:**
- [ ] Add Redis caching
- [ ] Database query optimization
- [ ] CDN integration (Cloudflare)
- [ ] Lazy loading images
- [ ] Asset versioning
- [ ] Gzip compression
- [ ] Database indexing review

### 4.3 Real PWA Offline Reader
**Status:** Basic service worker exists | **Priority:** 🟢 Low

**Tasks:**
- [ ] IndexedDB for offline storage
- [ ] Cache PDFs for offline reading
- [ ] Sync reading progress when back online
- [ ] Add "install app" banner
- [ ] Offline indicator

### 4.4 Analytics & Insights
**Status:** Not Started | **Priority:** 🟢 Low

**Track:**
- Most read books
- Reading duration
- Popular categories
- Trending districts
- Search analytics
- User engagement

## Phase 5: Cultural Expansion (Priority: Future)

### 5.1 Rich Media Content
- [ ] Folk music audio player
- [ ] Manuscript image gallery
- [ ] Interactive Rajasthan map
- [ ] Festival calendar
- [ ] Oral history audio recordings

### 5.2 Community Features
- [ ] User reviews and ratings
- [ ] Book collections/favorites
- [ ] Reading lists
- [ ] User profiles
- [ ] Discussion forums

### 5.3 Mobile App
- [ ] React Native / Flutter app
- [ ] Native PDF reader
- [ ] Push notifications
- [ ] Offline downloads

## Implementation Priority Order

### Start Now (Week 1-2):
1. ✅ TailwindCSS + Vite setup
2. ✅ Cover image upload system
3. ✅ Slug generator
4. ✅ Search engine (FULLTEXT)

### Next (Week 3-4):
5. Admin dashboard with charts
6. Enhanced PDF reader
7. Book preview pages

### Then (Month 2):
8. Multi-language support
9. OCR integration
10. Queue system

### Production Prep (Month 3):
11. Security hardening
12. Performance optimization
13. CDN setup

## Quick Wins for Immediate Impact

### 1. Fix These Views (30 minutes each):
- [ ] `books/show.php` - Add cover image display
- [ ] `books/index.php` - Add grid with covers
- [ ] `authors/index.php` - Add author cards
- [ ] `admin/books/index.php` - Add thumbnails

### 2. Add These Helpers:
- [ ] Image resize helper
- [ ] Slug generator helper
- [ ] Search highlight helper

### 3. Create These Pages:
- [ ] About RajasthanReads
- [ ] Contact page
- [ ] Privacy Policy
- [ ] Terms of Service

## Success Metrics to Track

- **SEO:** Organic traffic, keyword rankings
- **Engagement:** Time on site, pages per session
- **Content:** Books uploaded, active readers
- **Performance:** Page load times, Core Web Vitals
- **Mobile:** PWA installs, mobile usage %

## Notes

- Current platform is **MVP-ready**
- Focus on **content first** - add 50+ books
- **SEO pages** will drive traffic
- **OCR search** is the killer feature
- Keep **cultural authenticity** - this is the differentiator

---

Last Updated: May 2026
Next Review: After Phase 1 completion
