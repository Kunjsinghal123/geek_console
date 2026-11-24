🔑 1. Search Analytics (Performance Data)

This is the most-used endpoint: searchanalytics.query

What you get:

Queries (keywords users searched)
Pages (landing URLs)
Countries
Devices (mobile, desktop, tablet)
Search appearance (rich results, AMP, etc.)
Dates

Metrics:

Clicks
Impressions
CTR (click-through rate)
Average position

👉 This is where your “keywords” data comes from.

📄 2. URL Inspection API

Endpoint: urlInspection.index.inspect

What you get:

Index status (Indexed / Not indexed)
Coverage state (e.g. Crawled but not indexed)
Last crawl date
Canonical URL (Google-selected)
Mobile usability status
Rich results eligibility

👉 This replaces the manual “Inspect URL” tool.

🗺️ 3. Sitemaps API

Endpoint: sitemaps

What you get:

Submitted sitemap URLs
Last submitted date
Last downloaded (by Google)
Status (Success / Has errors)
Number of URLs discovered

👉 Useful for monitoring sitemap health.

🕷️ 4. Index Coverage (via URL Inspection, partially)

There is no direct “Coverage report API”, but you can infer via:

URL Inspection (per URL)
Bulk checks (you must loop URLs yourself)

Coverage types include:

Valid
Valid with warnings
Excluded
Errors
🔗 5. Links API

Endpoint: links

What you get:

External backlinks (top linking sites)
Top linked pages
Anchor text (top linking text)
Internal links

👉 Note: This is sampled data, not a full backlink database like Ahrefs.

⚙️ 6. Sites API

Endpoint: sites

What you get:

List of properties (websites) you have access to
Permission level (owner, full user, etc.)
🚫 What You CANNOT Get via API

This is important — many expect more than what's available.

❌ No full crawl stats (like Crawl Stats report in UI)
❌ No Core Web Vitals raw API (limited via CrUX instead)
❌ No manual actions API
❌ No security issues API
❌ No full index coverage report in bulk
❌ No real-time crawling logs

📊 Summary Table
Feature	Available via API
Keywords (queries)	✅ Yes
Clicks / Impressions / CTR	✅ Yes
Rankings (avg position)	✅ Yes
Index status (per URL)	✅ Yes
Sitemap data	✅ Yes
Backlinks (limited)	✅ Yes
Crawl stats	❌ No
Core Web Vitals	❌ Not directly
Manual penalties	❌ No