    <footer class="footer">
        <div class="wrapper">
            <div class="footer-header">
                <h2>Get in touch.</h2>
            </div>
            <div class="footer-grid">
                <div class="footer-col">
                    <h3><i class="fi fi-rr-apps"></i> Categories</h3>
                    <ul>
                        @foreach($categories as $category)
                            <li><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-col">
                    <h3><i class="fi fi-rr-cloud"></i> Policies</h3>
                    <ul>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Exchange</a></li>
                        <li><a href="#">Buy back Policy</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3><i class="fi fi-rr-briefcase"></i> About us</h3>
                    <ul>
                        <li><a href="#">Who we are</a></li>
                        <li><a href="#">What we do</a></li>
                        <li><a href="#">Contacts</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3><i class="fi fi-rr-info"></i> Socials</h3>
                    <ul>
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Approach</a></li>
                        <li><a href="#">Success Stories</a></li>
                        <li><a href="#">Insights</a></li>
                        <li><a href="#">Work at</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col newsletter">
                    <h3><i class="fi fi-rr-envelope"></i> Newsletter</h3>
                    <form>
                        <label for="email">Email *</label>
                        <input type="email" id="email" placeholder="" />
                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" />
                            <label for="terms">I agree with the terms and conditions and the privacy statement.</label>
                        </div>
                        <button type="submit">To send</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="social-icons">
                    <a href="#"><i class="fi fi-brands-instagram"></i></a>
                    <a href="#"><i class="fi fi-brands-linkedin"></i></a>
                    <a href="#"><i class="fi fi-brands-facebook"></i></a>
                    <a href="#"><i class="fi fi-brands-whatsapp"></i></a>
                    <a href="#"><i class="fi fi-rr-envelope"></i></a>
                </div>
                <div class="partners">
                    <!-- Placeholders for partner logos -->
                    <span>Microsoft Partner</span>
                    <span>Google Partner</span>
                    <span>ISO 27001:2017</span>
                </div>
                <div class="legal-links">
                    <a href="#">Privacy statement</a>
                    <a href="#">Terms and Conditions</a>
                </div>
            </div>
        </div>
    </footer>