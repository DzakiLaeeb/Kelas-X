<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dzexz Portfolio</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #0a0a0a;
            color: #fff;
            overflow-x: hidden;
        }

        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('/api/placeholder/1920/1080') center/cover;
            z-index: -1;
        }

        .hero-content {
            text-align: center;
            z-index: 1;
        }

        .hero h1 {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.5rem;
            opacity: 0.8;
        }

        section {
            padding: 100px 20px;
            position: relative;
        }

        .about {
            background: #111;
        }

        .projects {
            background: #0a0a0a;
        }

        .contact {
            background: #111;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            font-size: 3rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .project-card {
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-10px);
        }

        .project-img {
            width: 100%;
            height: 200px;
            background: url('/api/placeholder/400/200') center/cover;
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-content h3 {
            margin-bottom: 1rem;
        }

        .project-content p {
            opacity: 0.8;
            line-height: 1.6;
        }

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 3rem;
            justify-content: center;
        }

        .skill-item {
            background: #1a1a1a;
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: bold;
        }

        .contact-form {
            max-width: 600px;
            margin: 0 auto;
        }

        input, textarea {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #1a1a1a;
            border: none;
            border-radius: 5px;
            color: #fff;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #45a049;
        }

        footer {
            text-align: center;
            padding: 2rem;
            background: #0a0a0a;
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="hero-content">
            <h1>Dzexz</h1>
            <p>Creative Developer & Designer</p>
            <p style="color: red; padding-top: 55px"><strong>VALORANT PLAYER</strong></p>
        </div>
    </div>

    <section class="about">
        <div class="container">
            <h2>About Me</h2>
            <p>I'm a passionate developer and designer with a keen eye for detail and a love for creating beautiful, functional websites. With years of experience in web development, I bring ideas to life through clean code and stunning designs.</p>
            
            <div class="skills">
                <div class="skill-item">HTML5</div>
                <div class="skill-item">CSS3</div>
                <div class="skill-item">JavaScript</div>
                <div class="skill-item">React</div>
                <div class="skill-item">Node.js</div>
                <div class="skill-item">UI/UX Design</div>
            </div>
        </div>
    </section>

    <section class="projects">
        <div class="container">
            <h2>My Projects</h2>
            <div class="project-grid">
                <div class="project-card">
                    <div class="project-img"></div>
                    <div class="project-content">
                        <h3>Project One</h3>
                        <p>A modern web application built with React and Node.js</p>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img"></div>
                    <div class="project-content">
                        <h3>Project Two</h3>
                        <p>E-commerce platform with advanced features</p>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img"></div>
                    <div class="project-content">
                        <h3>Project Three</h3>
                        <p>Mobile-first responsive design portfolio</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <h2>Contact Me</h2>
            <form class="contact-form">
                <input type="text" placeholder="Name" required>
                <input type="email" placeholder="Email" required>
                <textarea rows="5" placeholder="Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Dzexz. All rights reserved.</p>
    </footer>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Parallax effect for hero section
        gsap.to(".hero::before", {
            yPercent: 50,
            ease: "none",
            scrollTrigger: {
                trigger: ".hero",
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });

        // Animate project cards on scroll
        gsap.utils.toArray('.project-card').forEach(card => {
            gsap.from(card, {
                y: 50,
                opacity: 0,
                duration: 1,
                scrollTrigger: {
                    trigger: card,
                    start: "top bottom-=100",
                    toggleActions: "play none none reverse"
                }
            });
        });

        // Animate skill items
        gsap.utils.toArray('.skill-item').forEach((skill, i) => {
            gsap.from(skill, {
                scale: 0,
                opacity: 0,
                duration: 0.5,
                delay: i * 0.1,
                scrollTrigger: {
                    trigger: '.skills',
                    start: "top center"
                }
            });
        });
    </script>
</body>
</html>