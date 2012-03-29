<?php

namespace Prism\PollBundle\Entity;

/**
 * Prism\PollBundle\Entity\BaseChoice
 */
abstract class BaseChoice
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var integer $votes
     */
    protected $votes;

    /**
     * @var integer $ordering
     */
    protected $ordering;

    /**
     * @var datetime $createdAt
     */
    protected $createdAt;

    /**
     * @var datetime $updatedAt
     */
    protected $updatedAt;

    /**
     * @var Application\Prism\PollBundle\Entity\Choice
     */
    protected $poll;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set votes
     *
     * @param integer $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    /**
     * Get votes
     *
     * @return integer 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set poll
     *
     * @param Application\Prism\PollBundle\Entity\Poll $poll
     */
    public function setPoll(\Application\Prism\PollBundle\Entity\Poll $poll)
    {
        $this->poll = $poll;
    }

    /**
     * Get poll
     *
     * @return Application\Prism\PollBundle\Entity\Poll 
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @ORM\prePersist
     */
    public function prePersist()
    {

    }

    /**
     * @ORM\preUpdate
     */
    public function preUpdate()
    {

    }
}