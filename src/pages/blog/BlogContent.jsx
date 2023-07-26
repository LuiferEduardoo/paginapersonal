import React from "react";
import { Helmet } from "react-helmet";
import { Link } from "react-router-dom";
import styles from "../../assets/styles/blog.module.css";

const BlogContent = ({ blogPosts }) => {
  const firstTwoPosts = blogPosts.slice(0, 2);
  const postsAfterFirstTwo = blogPosts.slice(2);

  console.log(blogPosts);
  return (
    <>
      <section className={styles.mainContainer}>
        <section className={styles.mainContainerBanner}>
          {firstTwoPosts.map((element, index) => (
            <div
              key={index}
              className={`${styles.post} ${
                index === 0 ? styles.firstPost : styles.seconPost
              }`}
            >
              <Link to={element.link}>
                <div>
                  <img src={element.image[0].url} />
                </div>
                <h1>{element.title}</h1>
                <span className={styles.date}>{element.date}</span>
                <span
                  className={styles.content}
                  dangerouslySetInnerHTML={{ __html: element.content }}
                />
              </Link>
            </div>
          ))}
        </section>
        <section className={styles.mainContainerAfterFirstTwo}>
          {postsAfterFirstTwo.map((element, index) => (
            <div key={index} className={styles.post}>
              <Link to={element.link}>
                <div>
                  <img src={element.image[0].url} />
                </div>
                <h1>{element.title}</h1>
                <span className={styles.date}>{element.date}</span>
                <span
                  className={styles.content}
                  dangerouslySetInnerHTML={{ __html: element.content }}
                />
              </Link>
            </div>
          ))}
        </section>
      </section>
    </>
  );
};

export { BlogContent };
