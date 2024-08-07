<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://osnovanie.info/</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://osnovanie.info/about</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://osnovanie.info/course</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://osnovanie.info/archive</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://osnovanie.info/lid/create</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>https://osnovanie.info/login</loc>
        <lastmod>2024-04-22T15:48:07+01:00</lastmod>
        <priority>0.9</priority>
    </url>
    @foreach($courses as $course)
        <url>
            <loc>https://osnovanie.info/course/{{$course->id}}</loc>
            <lastmod>{{$course->updated_at}}</lastmod>
            <priority>0.9</priority>
        </url>
        <url>
            <loc>https://osnovanie.info/lid/create/{{$course->id}}</loc>
            <lastmod>{{$course->updated_at}}</lastmod>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
