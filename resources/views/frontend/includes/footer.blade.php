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
                        <li><a href="#" style=" display: flex;align-items: center; gap: 5px; text-decoration: none;"><i class="fi fi-brands-instagram"></i> <span style="margin-bottom: 2px;">Instagram</span></a></li>
                        <li><a href="#" style=" display: flex;align-items: center; gap: 5px; text-decoration: none;"><i class="fi fi-brands-facebook"></i> <span style="margin-bottom: 2px;">Facebook</span></a></li>
                        <li><a href="#" style=" display: flex;align-items: center; gap: 5px; text-decoration: none;"><i class="fi fi-brands-linkedin"></i> <span style="margin-bottom: 2px;">LinkedIn</span></a></li>
                        <li><a href="#" style=" display: flex;align-items: center; gap: 5px; text-decoration: none;"><i class="fi fi-brands-pinterest"></i> <span style="margin-bottom: 2px;">Pinterest</span></a></li>
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
                    <a href="#" style="display:flex; justify-content:center; align-items:center;"><i class="fi fi-brands-instagram" style="width: 18px; height: 18px;"></i></a>
                    <a href="#" style="display:flex; justify-content:center; align-items:center;"><i class="fi fi-brands-facebook" style="width: 18px; height: 18px;"></i></a>
                    <a href="#" style="display:flex; justify-content:center; align-items:center;"><i class="fi fi-brands-linkedin" style="width: 18px; height: 18px;"></i></a>
                    <a href="#" style="display:flex; justify-content:center; align-items:center;"><i class="fi fi-brands-pinterest" style="width: 18px; height: 18px;"></i></a>
                </div>
                <div class="partners">
                    <!-- Placeholders for partner logos -->
                    <span><img src="{{asset('img/1.png')}}" alt="" class="footer-brands" style="height: 35px; width: auto; object-fit: contain; opacity: 0.9;"></span>
                    <span><img src="{{asset('img/2.png')}}" alt="" class="footer-brands" style="height: 35px; width: auto; object-fit: contain; opacity: 0.9;"></span>
                    <span><img src="{{asset('img/3.png')}}" alt="" class="footer-brands" style="height: 35px; width: auto; object-fit: contain; opacity: 0.9;"></span>
                </div>
                <div class="legal-links">
                    <a href="#">Privacy statement</a>
                    <a href="#">Terms and Conditions</a>
                </div>
            </div>
        </div>
    </footer>