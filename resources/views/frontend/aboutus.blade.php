@extends('frontend.layouts.app')

@section('title', 'About Us')

@section('content')
    <style>
        .about-section {
            padding: 60px 0;
        }

        .about-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .about-header h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .about-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .about-content-block {
            margin-bottom: 60px;
        }

        .about-content-block h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .value-item {
            text-align: center;
            padding: 20px;
            background: #fff7df;
            border-radius: 8px;
        }

        .value-item h3 {
            font-size: 1.25rem;
            margin-bottom: 15px;
            color: #333;
        }

        .value-item p {
            color: #666;
            line-height: 1.5;
        }

        .founders-section {
            background: #c8c4b1;
            padding: 60px 0;
        }

        .founders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .founder-card {
            background: #fff7df;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .founder-card h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        .founder-card .role {
            font-size: 0.9rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: block;
        }

        .founder-card p {
            color: #666;
            line-height: 1.6;
        }

        .contact-section {
            text-align: center;
            padding: 60px 0;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .contact-item h3 {
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: #333;
        }

        .contact-item p {
            color: #666;
        }
    </style>

    <div class="about-section">
        <div class="wrapper">
            <!-- Section 1: Who We Are -->
            <div class="about-header" id="who-we-are">
                <h1>About Savera</h1>
                <h2>For moments that matter</h2>
                <br>
                <p>
                    At Savera, we believe fine jewellery should never feel distant, intimidating, or out of reach.
                    It should feel honest. Personal. And achievable.
                </p>
                <br>
                <p>
                    Savera was born from a simple yet powerful idea: to make diamond jewellery more accessible without
                    compromising on trust, quality, or design. By working with lab-grown diamonds and silver, we are
                    redefining what luxury means for a new generation, one that aspires deeply, thinks practically, and
                    values transparency over excess.
                </p>
                <br>
                <p>
                    We exist at the intersection of aspiration and affordability, where everyday confidence meets timeless
                    elegance.
                </p>
                <br>
                <p>
                    We envision a future where owning fine jewellery is no longer a milestone reserved for a few, but a
                    meaningful choice available to many.
                </p>
            </div>

            <!-- Section 2: What We Do -->
            <div class="about-content-block" id="what-we-do">
                <h2>What We Stand For</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <h3>Trust first, always</h3>
                        <p>From sourcing to pricing to after-sales support, trust is the foundation of everything we do.</p>
                    </div>
                    <div class="value-item">
                        <h3>Affordability without apology</h3>
                        <p>We believe being affordable doesn’t mean being ordinary. It means being thoughtful, efficient,
                            and consumer-first.</p>
                    </div>
                    <div class="value-item">
                        <h3>Access over intimidation</h3>
                        <p>We’re building Savera for real people, real stories, and real aspirations, both online and
                            offline.</p>
                    </div>
                    <div class="value-item">
                        <h3>Modern aspiration</h3>
                        <p>Our designs are minimal, contemporary, and versatile, made for today’s woman, her milestones, and
                            her everyday confidence.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Our Story -->
    <div class="founders-section">
        <div class="wrapper">
            <div class="about-content-block" style="margin-bottom: 0;" id="our-story">
                <h2>Our Story</h2>
                <p style="text-align: center; max-width: 800px; margin: 0 auto; color: #666; line-height: 1.6;">
                    Savera is built by people who come from different worlds, united by a shared belief: jewellery should
                    feel empowering, not overwhelming.
                </p>

                <div class="founders-grid">
                    <!-- Danish Hafiz -->
                    <div class="founder-card">
                        <h3>Danish Hafiz</h3>
                        <span class="role">Co-Founder</span>
                        <p>
                            Danish brings a sharp business and brand-building lens to Savera. With experience across
                            distribution, marketing, and scaling consumer businesses, he focuses on building Savera as a
                            trusted, process-driven, and future-ready brand—one that balances ambition with vision and
                            scale.
                        </p>
                    </div>

                    <!-- Priyanka Bhattacharya -->
                    <div class="founder-card">
                        <h3>Priyanka Bhattacharjee</h3>
                        <span class="role">Co-Founder</span>
                        <p>
                            Priyanka brings a rare blend of cultural influence and consumer intuition to Savera. With a
                            widespread social following, she is a familiar and trusted face across Bengal, with a strong
                            presence in Tollywood and popular web series.
                        </p>
                        <br>
                        <p>
                            Beyond visibility, Priyanka’s strength lies in her deep understanding of today’s audience, how
                            they discover brands, what they emotionally connect with, and how aspiration is shaped in a
                            digital-first world. Her journey across entertainment and public platforms allows Savera to
                            speak authentically to a new generation that values relatability as much as refinement.
                        </p>
                    </div>

                    <!-- Iqbal Hussain -->
                    <div class="founder-card">
                        <h3>Iqbal Hussain</h3>
                        <span class="role">Co-Founder</span>
                        <p>
                            A gemologist with over 25 years of hands-on experience in diamonds and gemstones. His expertise
                            anchors the brand in authenticity, quality control, and deep technical knowledge—ensuring every
                            piece meets the highest standards of craftsmanship and integrity.
                        </p>
                    </div>
                </div>

                <p style="text-align: center; margin-top: 40px; color: #666;">
                    Together, the three founders bring emotion, execution, and expertise—a rare combination that defines
                    Savera’s DNA.
                </p>
            </div>
        </div>
    </div>

    <!-- Section 4: Contact -->
    <div class="contact-section" id="contact">
        <div class="wrapper">
            <div class="contact-info">
                <div class="contact-item">
                    <h3>Contact</h3>
                    <p>digital@mysavera.in</p>
                </div>
                <div class="contact-item">
                    <h3>Stores</h3>
                    <p>Zakaria Street, Kolkata</p>
                </div>
            </div>
        </div>
    </div>

@endsection
