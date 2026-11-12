@push('styles')
    <style>
        .help-wrap {
            --help-blue: #094ca1;
            --help-green: #1f8a42;
            --help-text: #3d444b;
            --help-muted: #6c757d;
            --help-border: #e3e6ea;
            --help-bg: #f7fbf8;
            max-width: 1120px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
            color: var(--help-text);
        }
        .help-hero {
            margin-bottom: 1.75rem;
            padding: 1.5rem 1.35rem;
            border: 1px solid #c5e6ce;
            border-radius: 1rem;
            background: linear-gradient(135deg, #e5f6ea 0%, #f3fbf5 55%, #ffffff 100%);
        }
        .help-hero h1 {
            margin: 0 0 0.5rem;
            color: var(--help-green);
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.25;
        }
        .help-hero p {
            margin: 0;
            color: var(--help-text);
            font-size: 1.05rem;
            line-height: 1.5;
        }
        .help-layout {
            display: grid;
            gap: 1.5rem;
        }
        @media (min-width: 992px) {
            .help-layout {
                grid-template-columns: 260px minmax(0, 1fr);
                align-items: start;
            }
        }
        .help-nav {
            padding: 1rem;
            border: 1px solid var(--help-border);
            border-radius: 1rem;
            background: linear-gradient(180deg, #ffffff 0%, var(--help-bg) 100%);
        }
        .help-nav-title {
            margin: 0 0 0.75rem;
            color: var(--help-blue);
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .help-nav-group {
            margin-bottom: 1rem;
        }
        .help-nav-group:last-child {
            margin-bottom: 0;
        }
        .help-nav-group-title {
            margin: 0 0 0.35rem;
            color: var(--help-muted);
            font-size: 0.8rem;
            font-weight: 700;
        }
        .help-nav a {
            display: block;
            padding: 0.35rem 0.5rem;
            border-radius: 0.45rem;
            color: var(--help-blue);
            text-decoration: none;
            font-size: 0.95rem;
            line-height: 1.35;
        }
        .help-nav a:hover,
        .help-nav a:focus {
            background: rgba(9, 76, 161, 0.08);
            color: var(--help-blue);
            text-decoration: none;
        }
        .help-nav a.is-active {
            background: rgba(31, 138, 66, 0.12);
            color: var(--help-green);
            font-weight: 700;
        }
        .help-card {
            padding: 1.35rem 1.25rem;
            border: 1px solid var(--help-border);
            border-radius: 1rem;
            background: #fff;
        }
        .help-card h2.help-section-title {
            margin: 0 0 1rem;
            color: var(--help-blue);
            font-size: 1.35rem;
            font-weight: 700;
        }
        .help-toc-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .help-toc-list li {
            margin-bottom: 0.45rem;
        }
        .help-toc-list a {
            display: block;
            padding: 0.7rem 0.85rem;
            border: 1px solid var(--help-border);
            border-radius: 0.75rem;
            color: inherit;
            text-decoration: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .help-toc-list a:hover,
        .help-toc-list a:focus {
            border-color: #1f8a42;
            box-shadow: 0 6px 18px rgba(31, 138, 66, 0.12);
            text-decoration: none;
            color: inherit;
        }
        .help-toc-list strong {
            display: block;
            color: var(--help-blue);
            font-size: 1.05rem;
        }
        .help-article {
            line-height: 1.65;
        }
        .help-article > *:first-child {
            margin-top: 0;
        }
        .help-article h1,
        .help-article h2,
        .help-article h3,
        .help-article h4 {
            color: var(--help-blue);
            font-weight: 700;
            line-height: 1.3;
            margin-top: 1.6rem;
            margin-bottom: 0.75rem;
        }
        .help-article h1 { font-size: 1.7rem; margin-top: 0; }
        .help-article h2 { font-size: 1.35rem; padding-bottom: 0.35rem; border-bottom: 1px solid var(--help-border); }
        .help-article h3 { font-size: 1.15rem; }
        .help-article p,
        .help-article ul,
        .help-article ol {
            margin-bottom: 1rem;
        }
        .help-article a {
            color: var(--help-green);
            font-weight: 600;
        }
        .help-article table {
            width: 100%;
            margin: 1rem 0 1.25rem;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        .help-article th,
        .help-article td {
            padding: 0.55rem 0.65rem;
            border: 1px solid var(--help-border);
            vertical-align: top;
        }
        .help-article th {
            background: #f3fbf5;
            color: var(--help-blue);
            font-weight: 700;
        }
        .help-article img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 1rem 0 1.25rem;
            border: 1px solid var(--help-border);
            border-radius: 0.65rem;
            box-shadow: 0 8px 24px rgba(9, 76, 161, 0.08);
            background: #fff;
        }
        .help-article code {
            padding: 0.1rem 0.35rem;
            border-radius: 0.3rem;
            background: #f3f4f6;
            font-size: 0.9em;
        }
        .help-article pre {
            padding: 0.85rem 1rem;
            overflow-x: auto;
            border-radius: 0.65rem;
            background: #f3f4f6;
            border: 1px solid var(--help-border);
        }
        .help-article pre code {
            padding: 0;
            background: transparent;
        }
        .help-article hr {
            margin: 1.5rem 0;
            border: 0;
            border-top: 1px solid var(--help-border);
        }
        .help-article blockquote {
            margin: 1rem 0;
            padding: 0.75rem 1rem;
            border-left: 4px solid var(--help-green);
            background: var(--help-bg);
            color: var(--help-text);
        }
        .help-pager {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--help-border);
        }
        .help-pager a {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.9rem;
            border-radius: 0.65rem;
            border: 1px solid var(--help-border);
            color: var(--help-blue);
            font-weight: 700;
            text-decoration: none;
        }
        .help-pager a:hover,
        .help-pager a:focus {
            border-color: var(--help-green);
            color: var(--help-green);
            text-decoration: none;
        }
        .help-back {
            display: inline-flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--help-blue);
            font-weight: 700;
            text-decoration: none;
        }
        .help-back:hover,
        .help-back:focus {
            color: var(--help-green);
            text-decoration: none;
        }
    </style>
@endpush
