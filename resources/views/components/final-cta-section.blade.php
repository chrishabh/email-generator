<section class="final-cta-section py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-center wow fadeInUp" data-wow-delay="0.3s">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <h1 class="fw-bold mb-3" style="font-size: 2.2rem;">
          Start Using the #1 Email Verification Tool Today
        </h1>
        <p class="lead mb-4" style="font-size: 1.1rem;">
          Subscription plans available. Fast setup. Instant results.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <a href="{{ url('/signup') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 shadow-sm fw-semibold hover:opacity-90">
            Sign Up
          </a>
          <a href="{{ url('/signin') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold hover:bg-white hover:text-blue-700">
            Verify Emails Instantly
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .final-cta-section {
    background: rgb(10, 93, 170);
  }
  .final-cta-section .btn {
    transition: all 0.3s ease;
  }
  .final-cta-section .btn:hover {
    transform: translateY(-3px);
  }
</style>
